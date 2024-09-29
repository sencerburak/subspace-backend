<?php

declare(strict_types=1);

namespace App\Application\Actions\Message;

use App\Application\Actions\Action;
use App\Domain\Message\MessageRepository;
use App\Domain\ChatGroup\ChatGroupRepository;
use Psr\Log\LoggerInterface;

abstract class MessageAction extends Action
{
    protected MessageRepository $messageRepository;
    protected ChatGroupRepository $chatGroupRepository;

    public function __construct(
        LoggerInterface $logger,
        MessageRepository $messageRepository,
        ChatGroupRepository $chatGroupRepository
    ) {
        parent::__construct($logger);
        $this->messageRepository = $messageRepository;
        $this->chatGroupRepository = $chatGroupRepository;
    }
}
