<?php

declare(strict_types=1);

namespace Tests\Domain\ChatGroup;

use App\Domain\ChatGroup\ChatGroup;
use Tests\TestCase;

class ChatGroupTest extends TestCase
{
    public function chatGroupProvider(): array
    {
        return [
            [1, 'General'],
            [2, 'Random'],
            [3, 'Tech Talk'],
        ];
    }

    /**
     * @dataProvider chatGroupProvider
     * @param int    $id
     * @param string $name
     */
    public function testGetters(int $id, string $name)
    {
        $chatGroup = new ChatGroup($id, $name);

        $this->assertEquals($id, $chatGroup->getId());
        $this->assertEquals($name, $chatGroup->getName());
    }

    /**
     * @dataProvider chatGroupProvider
     * @param int    $id
     * @param string $name
     */
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
