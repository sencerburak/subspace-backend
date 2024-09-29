<?php

declare(strict_types=1);

namespace App\Application\Actions\ChatGroup;

use Psr\Http\Message\ResponseInterface;
use App\Domain\DomainException\DomainRecordNotFoundException;

class JoinChatGroupAction extends ChatGroupAction
{
    protected function action(): ResponseInterface
    {
        $groupId = (int) $this->resolveArg('id');
        $userId = (int) $this->request->getAttribute('user')->getId();

        if ($this->chatGroupRepository->isUserInGroup($userId, $groupId)) {
            return $this->respondWithData(['message' => 'User is already in this group']);
        }

        try {
            $this->chatGroupRepository->joinChatGroup($groupId, $userId);
            return $this->respondWithData(['status' => 'success', 'message' => 'Joined group successfully']);
        } catch (DomainRecordNotFoundException $e) {
            return $this->respondWithData(['status' => 'error', 'message' => $e->getMessage()], 404);
        }
    }
}
