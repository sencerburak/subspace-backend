<?php

declare(strict_types=1);

use App\Application\Middleware\SessionMiddleware;
use App\Application\Middleware\UserIdentificationMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(SessionMiddleware::class);
    
    // Apply UserIdentificationMiddleware to all routes except /users (POST)
    $app->add(function ($request, $handler) use ($app) {
        $route = $request->getUri()->getPath();
        $method = $request->getMethod();
        
        if ($route === '/users' && $method === 'POST') {
            return $handler->handle($request);
        }
        
        return $app->getContainer()->get(UserIdentificationMiddleware::class)->process($request, $handler);
    });
};
