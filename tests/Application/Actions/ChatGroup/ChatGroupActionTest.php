<?php

declare(strict_types=1);

namespace Tests\Application\Actions\ChatGroup;

use App\Application\Actions\ActionPayload;
use App\Domain\ChatGroup\ChatGroupRepository;
use App\Domain\ChatGroup\ChatGroup;
use App\Domain\User\User;
use DI\Container;
use Tests\TestCase;

class ChatGroupActionTest extends TestCase
{
    public function testListChatGroups()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $chatGroup = new ChatGroup(1, 'Test Group');
        $user = new User(1, 'test_user');

        $this->mockUserIdentification(1, $user);

        $chatGroupRepositoryProphecy = $this->prophesize(ChatGroupRepository::class);
        $chatGroupRepositoryProphecy
            ->findAll()
            ->willReturn([$chatGroup])
            ->shouldBeCalledOnce();

        $container->set(ChatGroupRepository::class, $chatGroupRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/chat-groups', ['User-Id' => '1']);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, [$chatGroup]);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testCreateChatGroup()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $chatGroup = new ChatGroup(1, 'New Group');
        $user = new User(1, 'test_user');

        $this->mockUserIdentification(1, $user);

        $chatGroupRepositoryProphecy = $this->prophesize(ChatGroupRepository::class);
        $chatGroupRepositoryProphecy
            ->createChatGroup('New Group')
            ->willReturn($chatGroup)
            ->shouldBeCalledOnce();

        $container->set(ChatGroupRepository::class, $chatGroupRepositoryProphecy->reveal());

        $request = $this->createRequest('POST', '/chat-groups', ['User-Id' => '1'])
            ->withParsedBody(['name' => 'New Group']);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $chatGroup);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
