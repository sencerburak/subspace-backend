<?php

declare(strict_types=1);

namespace App\Application\Actions\ChatGroup;

use Psr\Http\Message\ResponseInterface;

class CreateChatGroupAction extends ChatGroupAction
{
    protected function action(): ResponseInterface
    {
        $data = $this->getFormData();
        $name = $data['name'];
        $chatGroup = $this->chatGroupRepository->createChatGroup($name);
        return $this->respondWithData($chatGroup);
    }
}
