<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\User;

use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use App\Domain\DomainException\DatabaseOperationException;
use PDO;
use PDOException;

class SqliteUserRepository implements UserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM users');
            $users = [];
            while ($row = $stmt->fetch()) {
                $users[] = new User((int)$row['id'], $row['username']);
            }
            return $users;
        } catch (PDOException $e) {
            throw new DatabaseOperationException('Failed to fetch users: ' . $e->getMessage(), 0, $e);
        }
    }

    public function findUserOfId(int $id): User
    {
        try {
            $stmt = $this->db->prepare('SELECT * FROM users WHERE id = :id');
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch();

            if (!$row) {
                throw new UserNotFoundException();
            }

            return new User((int)$row['id'], $row['username']);
        } catch (PDOException $e) {
            throw new DatabaseOperationException('Failed to fetch user: ' . $e->getMessage(), 0, $e);
        }
    }

    public function createUser(string $username): User
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare('INSERT INTO users (username) VALUES (:username)');
            $stmt->execute(['username' => $username]);
            $id = $this->db->lastInsertId();
            $this->db->commit();
            return new User((int)$id, $username);
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw new DatabaseOperationException('Failed to create user: ' . $e->getMessage(), 0, $e);
        }
    }
}
