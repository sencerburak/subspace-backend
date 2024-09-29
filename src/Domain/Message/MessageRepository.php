<?php

declare(strict_types=1);

namespace App\Domain\Message;

interface MessageRepository
{
    /**
     * @param int $groupId
     * @return Message[]
     */
    public function findAllInGroup(int $groupId): array;

    /**
     * @param int $groupId
     * @param int $userId
     * @param string $content
     * @return Message
     */
    public function createMessage(int $groupId, int $userId, string $content): Message;
}
