<?php

declare(strict_types=1);

namespace Tests\Application\Actions\Message;

use App\Application\Actions\ActionPayload;
use App\Domain\Message\MessageRepository;
use App\Domain\Message\Message;
use App\Domain\ChatGroup\ChatGroupRepository;
use App\Domain\User\User;
use DI\Container;
use Tests\TestCase;

class MessageActionTest extends TestCase
{
    public function testListMessages()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $message = new Message(1, 1, 1, 'Test message', '2023-01-01 00:00:00');
        $user = new User(1, 'test_user');

        $this->mockUserIdentification(1, $user);

        $messageRepositoryProphecy = $this->prophesize(MessageRepository::class);
        $messageRepositoryProphecy
            ->findAllInGroup(1)
            ->willReturn([$message])
            ->shouldBeCalledOnce();

        $chatGroupRepositoryProphecy = $this->prophesize(ChatGroupRepository::class);
        $chatGroupRepositoryProphecy
            ->isUserInGroup(1, 1)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $container->set(MessageRepository::class, $messageRepositoryProphecy->reveal());
        $container->set(ChatGroupRepository::class, $chatGroupRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/chat-groups/1/messages', ['User-Id' => '1']);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$message]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testSendMessage()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $message = new Message(1, 1, 1, 'New message', '2023-01-01 00:00:00');
        $user = new User(1, 'test_user');

        $this->mockUserIdentification(1, $user);

        $messageRepositoryProphecy = $this->prophesize(MessageRepository::class);
        $messageRepositoryProphecy
            ->createMessage(1, 1, 'New message')
            ->willReturn($message)
            ->shouldBeCalledOnce();

        $chatGroupRepositoryProphecy = $this->prophesize(ChatGroupRepository::class);
        $chatGroupRepositoryProphecy
            ->isUserInGroup(1, 1)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $container->set(MessageRepository::class, $messageRepositoryProphecy->reveal());
        $container->set(ChatGroupRepository::class, $chatGroupRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/chat-groups/1/messages', ['User-Id' => '1'])
            ->withParsedBody(['content' => 'New message']);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $message);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
