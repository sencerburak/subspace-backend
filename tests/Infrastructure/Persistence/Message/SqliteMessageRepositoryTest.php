<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\Message;

use App\Domain\Message\Message;
use App\Infrastructure\Persistence\Message\SqliteMessageRepository;
use Tests\TestCase;
use PDO;

class SqliteMessageRepositoryTest extends TestCase
{
    private $pdo;
    private $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('
            CREATE TABLE messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                group_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                content TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ');
        $this->repository = new SqliteMessageRepository($this->pdo);
    }

    public function testFindAllInGroup()
    {
        $this->pdo->exec("
            INSERT INTO messages (group_id, user_id, content, created_at)
            VALUES 
                (1, 1, 'Message 1', '2023-06-01 12:00:00'),
                (1, 2, 'Message 2', '2023-06-01 12:01:00'),
                (2, 1, 'Message 3', '2023-06-01 12:02:00')
        ");

        $messages = $this->repository->findAllInGroup(1);
        $this->assertCount(2, $messages);
        $this->assertInstanceOf(Message::class, $messages[0]);
        $this->assertEquals('Message 1', $messages[0]->getContent());
        $this->assertEquals('Message 2', $messages[1]->getContent());
    }

    public function testCreateMessage()
    {
        $message = $this->repository->createMessage(1, 1, 'New Message');
        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals('New Message', $message->getContent());
        $this->assertNotNull($message->getId());

        $stmt = $this->pdo->query('SELECT * FROM messages');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertEquals('New Message', $result['content']);
        $this->assertEquals(1, $result['group_id']);
        $this->assertEquals(1, $result['user_id']);
    }
}
