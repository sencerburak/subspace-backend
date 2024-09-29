<?php

declare(strict_types=1);

namespace App\Domain\Message;

use JsonSerializable;

class Message implements JsonSerializable
{
    private ?int $id;
    private int $groupId;
    private int $userId;
    private string $content;
    private string $createdAt;

    public function __construct(?int $id, int $groupId, int $userId, string $content, string $createdAt)
    {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->userId = $userId;
        $this->content = $content;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupId(): int
    {
        return $this->groupId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'groupId' => $this->groupId,
            'userId' => $this->userId,
            'content' => $this->content,
            'createdAt' => $this->createdAt,
        ];
    }
}
