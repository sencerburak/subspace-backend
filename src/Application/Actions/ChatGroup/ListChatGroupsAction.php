<?php

declare(strict_types=1);

namespace App\Application\Actions\ChatGroup;

use Psr\Http\Message\ResponseInterface;

class ListChatGroupsAction extends ChatGroupAction
{
    protected function action(): ResponseInterface
    {
        $chatGroups = $this->chatGroupRepository->findAll();
        return $this->respondWithData($chatGroups);
    }
}
