<?php

declare(strict_types=1);

namespace App\Domain\ChatGroup;

interface ChatGroupRepository
{
    /**
     * @return ChatGroup[]
     */
    public function findAll(): array;

    /**
     * @param string $name
     * @return ChatGroup
     */
    public function createChatGroup(string $name): ChatGroup;

    /**
     * @param int $groupId
     * @param int $userId
     */
    public function joinChatGroup(int $groupId, int $userId): void;

    /**
     * @param int $userId
     * @param int $groupId
     * @return bool
     */
    public function isUserInGroup(int $userId, int $groupId): bool;

    /**
     * @param int $userId
     * @return ChatGroup[]
     */
    public function getUserGroups(int $userId): array;
}