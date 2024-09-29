<?php

declare(strict_types=1);

namespace App\Domain\User;

use JsonSerializable;

class User implements JsonSerializable
{
    private ?int $id;
    private string $username;

    public function __construct(?int $id, string $username)
    {
        $this->id = $id;
        $this->username = $username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
        ];
    }
}
