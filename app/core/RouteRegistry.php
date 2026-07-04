<?php
declare(strict_types=1);

class RouteRegistry
{
    // EINZIGE Quelle für alle Routen im Projekt
    public const ROUTES = [
        // [method, pattern,        controller,             action,   public]
        ['GET',     '',             HomeController::class, 'index',    true],
        ['POST',   'auth/register', AuthController::class, 'register', true],
        ['POST',   'auth/login',    AuthController::class, 'login',    true],

        ['GET',    'todos',         TodoController::class, 'index',    false],
        ['GET',    'todos/{id}',    TodoController::class, 'show',     false],
        ['POST',   'todos',         TodoController::class, 'store',    false],
        ['PUT',    'todos/{id}',    TodoController::class, 'update',   false],
        ['DELETE', 'todos/{id}',    TodoController::class, 'destroy',  false],
    ];
}