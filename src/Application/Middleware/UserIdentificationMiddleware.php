<?php

declare(strict_types=1);

namespace App\Application\Middleware;

use App\Domain\User\UserRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface as Middleware;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpUnauthorizedException;

class UserIdentificationMiddleware implements Middleware
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $userId = $request->getHeaderLine('User-Id');
        if (empty($userId)) {
            throw new HttpUnauthorizedException($request, 'User-Id header is required');
        }
        
        try {
            $user = $this->userRepository->findUserOfId((int) $userId);
            if ($user === null) {
                throw new HttpUnauthorizedException($request, 'Invalid User-Id');
            }
            return $handler->handle($request->withAttribute('user', $user));
        } catch (\Exception $e) {
            throw new HttpUnauthorizedException($request, 'Invalid User-Id');
        }
    }
}