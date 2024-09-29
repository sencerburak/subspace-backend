<?php

declare(strict_types=1);

namespace Tests\Application\Actions\User;

use App\Application\Actions\ActionPayload;
use App\Domain\User\User;
use App\Domain\User\UserNotFoundException;
use App\Domain\User\UserRepository;
use DI\Container;
use Tests\TestCase;
use Slim\Exception\HttpUnauthorizedException;

class ViewUserActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        $user = new User(1, 'bill.gates');

        $this->mockUserIdentification(1, $user);

        $this->userRepository
            ->findUserOfId(1)
            ->willReturn($user)
            ->shouldBeCalledTimes(2);

        $request = $this->createRequest('GET', '/users/1', ['User-Id' => '1']);
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $user);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}
