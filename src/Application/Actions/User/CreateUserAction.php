<?php

declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class CreateUserAction extends UserAction
{
    protected function action(): Response
    {
        $data = $this->getFormData();
        $username = $data['username'];
        $user = $this->userRepository->createUser($username);
        $this->logger->info("User of id `{$user->getId()}` was created.");
        return $this->respondWithData($user);
    }
}
