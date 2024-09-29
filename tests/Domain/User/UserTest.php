<?php

declare(strict_types=1);

namespace Tests\Domain\User;

use App\Domain\User\User;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class UserTest extends TestCase
{
    public static function userProvider(): array
    {
        return [
            [1, 'bill.gates'],
            [2, 'steve.jobs'],
            [3, 'mark.zuckerberg'],
            [4, 'evan.spiegel'],
            [5, 'jack.dorsey'],
        ];
    }

    #[DataProvider('userProvider')]
    public function testGetters(int $id, string $username)
    {
        $user = new User($id, $username);

        $this->assertEquals($id, $user->getId());
        $this->assertEquals($username, $user->getUsername());
    }

    #[DataProvider('userProvider')]
    public function testJsonSerialize(int $id, string $username)
    {
        $user = new User($id, $username);

        $expectedPayload = json_encode([
            'id' => $id,
            'username' => $username,
        ]);

        $this->assertEquals($expectedPayload, json_encode($user));
    }
}
