<?php

declare(strict_types=1);

use App\Domain\User\UserRepository;
use App\Infrastructure\Persistence\User\SqliteUserRepository;
use App\Domain\ChatGroup\ChatGroupRepository;
use App\Infrastructure\Persistence\ChatGroup\SqliteChatGroupRepository;
use App\Domain\Message\MessageRepository;
use App\Infrastructure\Persistence\Message\SqliteMessageRepository;
use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);
            $dbPath = $settings->get('db')['path'];
            return new PDO("sqlite:$dbPath");
        },

        UserRepository::class => function (ContainerInterface $c) {
            return new SqliteUserRepository($c->get(PDO::class));
        },

        ChatGroupRepository::class => function (ContainerInterface $c) {
            return new SqliteChatGroupRepository($c->get(PDO::class));
        },

        MessageRepository::class => function (ContainerInterface $c) {
            return new SqliteMessageRepository($c->get(PDO::class));
        },
    ]);
};