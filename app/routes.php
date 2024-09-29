<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\User\CreateUserAction;
use App\Application\Actions\ChatGroup\CreateChatGroupAction;
use App\Application\Actions\ChatGroup\ListChatGroupsAction;
use App\Application\Actions\ChatGroup\JoinChatGroupAction;
use App\Application\Actions\Message\SendMessageAction;
use App\Application\Actions\Message\ListMessagesAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello world!');
        return $response;
    });

    $app->post('/users', CreateUserAction::class);

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });

    $app->group('/chat-groups', function (Group $group) {
        $group->post('', CreateChatGroupAction::class);
        $group->get('', ListChatGroupsAction::class);
        $group->post('/{id}/join', JoinChatGroupAction::class);
        $group->post('/{id}/messages', SendMessageAction::class);
        $group->get('/{id}/messages', ListMessagesAction::class);
    });
};
