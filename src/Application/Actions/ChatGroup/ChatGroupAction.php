<?php

declare(strict_types=1);

namespace App\Application\Actions\ChatGroup;

use App\Application\Actions\Action;
use App\Domain\ChatGroup\ChatGroupRepository;
use Psr\Log\LoggerInterface;

abstract class ChatGroupAction extends Action
{
    protected ChatGroupRepository $chatGroupRepository;

    public function __construct(LoggerInterface $logger, ChatGroupRepository $chatGroupRepository)
    {
        parent::__construct($logger);
        $this->chatGroupRepository = $chatGroupRepository;
    }
}
