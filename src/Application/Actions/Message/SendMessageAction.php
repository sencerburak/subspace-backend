<?php

declare(strict_types=1);

namespace App\Application\Actions\Message;

use Psr\Http\Message\ResponseInterface;
use App\Domain\DomainException\DomainRecordNotFoundException;

class SendMessageAction extends MessageAction
{
    protected function action(): ResponseInterface
    {
        $groupId = (int) $this->resolveArg('id');
        $userId = (int) $this->request->getAttribute('user')->getId();

        if (!$this->chatGroupRepository->isUserInGroup($userId, $groupId)) {
            return $this->respondWithData(['status' => 'error', 'message' => 'User is not in this group'], 403);
        }

        $data = $this->getFormData();
        $content = $data['content'];

        try {
            $message = $this->messageRepository->createMessage($groupId, $userId, $content);
            return $this->respondWithData($message);
        } catch (DomainRecordNotFoundException $e) {
            return $this->respondWithData(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }
}
