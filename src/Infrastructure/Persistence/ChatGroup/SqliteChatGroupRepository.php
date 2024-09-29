<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\ChatGroup;

use App\Domain\ChatGroup\ChatGroup;
use App\Domain\ChatGroup\ChatGroupRepository;
use App\Domain\DomainException\DatabaseOperationException;
use PDO;
use PDOException;

class SqliteChatGroupRepository implements ChatGroupRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM chat_groups');
            $chatGroups = [];
            while ($row = $stmt->fetch()) {
                $chatGroups[] = new ChatGroup($row['id'], $row['name']);
            }
            return $chatGroups;
        } catch (PDOException $e) {
            throw new DatabaseOperationException('Failed to fetch chat groups: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createChatGroup(string $name): ChatGroup
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('INSERT INTO chat_groups (name) VALUES (:name)');
            $stmt->execute(['name' => $name]);
            $id = $this->db->lastInsertId();
            $this->db->commit();
            return new ChatGroup((int)$id, $name);
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new DatabaseOperationException('Failed to create chat group: ' . $e->getMessage(), 0, $e);
        }
    }

    public function joinChatGroup(int $groupId, int $userId): void
    {
        try {
            $this->db->beginTransaction();
            if (!$this->isUserInGroup($userId, $groupId)) {
                $stmt = $this->db->prepare('INSERT INTO user_group (user_id, group_id) VALUES (:user_id, :group_id)');
                $stmt->execute(['user_id' => $userId, 'group_id' => $groupId]);
            }
            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new DatabaseOperationException('Failed to join chat group: ' . $e->getMessage(), 0, $e);
        }
    }

    public function isUserInGroup(int $userId, int $groupId): bool
    {
        try {
            $stmt = $this->db->prepare('SELECT COUNT(*) FROM user_group WHERE user_id = :user_id AND group_id = :group_id');
            $stmt->execute(['user_id' => $userId, 'group_id' => $groupId]);
            return (bool)$stmt->fetchColumn();
        } catch (PDOException $e) {
            throw new DatabaseOperationException('Failed to check if user is in group: ' . $e->getMessage(), 0, $e);
        }
    }

    public function getUserGroups(int $userId): array
    {
        try {
            $stmt = $this->db->prepare('
                SELECT cg.id, cg.name 
                FROM chat_groups cg
                JOIN user_group ug ON cg.id = ug.group_id
                WHERE ug.user_id = :user_id
            ');
            $stmt->execute(['user_id' => $userId]);
            $groups = [];
            while ($row = $stmt->fetch()) {
                $groups[] = new ChatGroup($row['id'], $row['name']);
            }
            return $groups;
        } catch (PDOException $e) {
            throw new DatabaseOperationException('Failed to get user groups: ' . $e->getMessage(), 0, $e);
        }
    }
}