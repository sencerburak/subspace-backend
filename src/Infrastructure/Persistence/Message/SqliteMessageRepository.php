<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Message;

use App\Domain\Message\Message;
use App\Domain\Message\MessageRepository;
use App\Domain\DomainException\DatabaseOperationException;
use PDO;
use PDOException;

class SqliteMessageRepository implements MessageRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAllInGroup(int $groupId): array
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM messages WHERE group_id = :group_id ORDER BY created_at');
            $stmt->execute(['group_id' => $groupId]);
            $messages = [];
            while ($row = $stmt->fetch()) {
                $messages[] = new Message(
                    $row['id'],
                    $row['group_id'],
                    $row['user_id'],
                    $row['content'],
                    $row['created_at']
                );
            }
            return $messages;
        } catch (PDOException $e) {
            throw new DatabaseOperationException('Failed to fetch messages: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createMessage(int $groupId, int $userId, string $content): Message
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('
            INSERT INTO messages (group_id, user_id, content) 
            VALUES (:group_id, :user_id, :content)
            ');
            $stmt->execute(['group_id' => $groupId, 'user_id' => $userId, 'content' => $content]);
            $id = $this->db->lastInsertId();
            $createdAt = date('Y-m-d H:i:s');
            $this->db->commit();
            return new Message((int)$id, $groupId, $userId, $content, $createdAt);
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new DatabaseOperationException('Failed to create message: ' . $e->getMessage(), 0, $e);
        }
    }
}
