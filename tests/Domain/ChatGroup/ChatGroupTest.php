<?php

declare(strict_types=1);

namespace Tests\Domain\ChatGroup;

use App\Domain\ChatGroup\ChatGroup;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ChatGroupTest extends TestCase
{
    public static function chatGroupProvider(): array
    {
        return [
            [1, 'General'],
            [2, 'Random'],
            [3, 'Tech Talk'],
        ];
    }

    #[DataProvider('chatGroupProvider')]
    public function testGetters(int $id, string $name)
    {
        $chatGroup = new ChatGroup($id, $name);

        $this->assertEquals($id, $chatGroup->getId());
        $this->assertEquals($name, $chatGroup->getName());
    }

    #[DataProvider('chatGroupProvider')]
    public function testJsonSerialize(int $id, string $name)
    {
        $chatGroup = new ChatGroup($id, $name);

        $expectedPayload = json_encode([
            'id' => $id,
            'name' => $name,
        ]);

        $this->assertEquals($expectedPayload, json_encode($chatGroup));
    }
}
