<?php

declare(strict_types=1);

namespace Tests\Domain\Message;

use App\Domain\Message\Message;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function messageProvider(): array
    {
        return [
            [1, 1, 1, 'Hello, world!', '2023-06-01 12:00:00'],
            [2, 1, 2, 'How are you?', '2023-06-01 12:01:00'],
            [3, 2, 1, 'Testing, testing', '2023-06-01 12:02:00'],
        ];
    }

    /**
     * @dataProvider messageProvider
     * @param int    $id
     * @param int    $groupId
     * @param int    $userId
     * @param string $content
     * @param string $createdAt
     */
    public function testGetters(int $id, int $groupId, int $userId, string $content, string $createdAt)
    {
        $message = new Message($id, $groupId, $userId, $content, $createdAt);

        $this->assertEquals($id, $message->getId());
        $this->assertEquals($groupId, $message->getGroupId());
        $this->assertEquals($userId, $message->getUserId());
        $this->assertEquals($content, $message->getContent());
        $this->assertEquals($createdAt, $message->getCreatedAt());
    }

    /**
     * @dataProvider messageProvider
     * @param int    $id
     * @param int    $groupId
     * @param int    $userId
     * @param string $content
     * @param string $createdAt
     */
    public function testJsonSerialize(int $id, int $groupId, int $userId, string $content, string $createdAt)
    {
        $message = new Message($id, $groupId, $userId, $content, $createdAt);

        $expectedPayload = json_encode([
            'id' => $id,
            'groupId' => $groupId,
            'userId' => $userId,
            'content' => $content,
            'createdAt' => $createdAt,
        ]);

        $this->assertEquals($expectedPayload, json_encode($message));
    }
}
