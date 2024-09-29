<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\ChatGroup;

use App\Domain\ChatGroup\ChatGroup;
use App\Infrastructure\Persistence\ChatGroup\SqliteChatGroupRepository;
use Tests\TestCase;
use PDO;

class SqliteChatGroupRepositoryTest extends TestCase
{
    private $pdo;
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('
            CREATE TABLE chat_groups (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL UNIQUE
            );
            CREATE TABLE user_group (
                user_id INTEGER NOT NULL,
                group_id INTEGER NOT NULL,
                PRIMARY KEY (user_id, group_id)
            );
        ');
        $this->repository = new SqliteChatGroupRepository($this->pdo);
    }

    public function testFindAll()
    {
        $this->pdo->exec("INSERT INTO chat_groups (name) VALUES ('Group 1'), ('Group 2')");
        $chatGroups = $this->repository->findAll();
        $this->assertCount(2, $chatGroups);
        $this->assertInstanceOf(ChatGroup::class, $chatGroups[0]);
        $this->assertEquals('Group 1', $chatGroups[0]->getName());
        $this->assertEquals('Group 2', $chatGroups[1]->getName());
    }

    public function testCreateChatGroup()
    {
        $chatGroup = $this->repository->createChatGroup('New Group');
        $this->assertInstanceOf(ChatGroup::class, $chatGroup);
        $this->assertEquals('New Group', $chatGroup->getName());
        $this->assertNotNull($chatGroup->getId());

        $stmt = $this->pdo->query('SELECT * FROM chat_groups');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('New Group', $result['name']);
    }

    public function testJoinChatGroup()
    {
        $this->pdo->exec("INSERT INTO chat_groups (id, name) VALUES (1, 'Group 1')");
        $this->repository->joinChatGroup(1, 1);

        $stmt = $this->pdo->query('SELECT * FROM user_group');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals(1, $result['user_id']);
        $this->assertEquals(1, $result['group_id']);
    }
}
