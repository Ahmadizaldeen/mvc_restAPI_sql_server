<?php
declare(strict_types=1);

class Router
{
    private string $method;
    private string $uri;
    private array  $routes = [];
    private ?object $authUser = null;

    public function __construct(string $method, string $uri)
    {
        $this->method = $method;
        $this->uri    = $uri;
        $this->routes = [ // Alle Routen definieren and registeren
            // Auth — öffentlich
            ['POST', 'auth/register', 'AuthController', 'register'],
            ['POST', 'auth/login',    'AuthController', 'login'],

            // Todos — geschützt
            ['GET',    'todos',      'TodoController', 'index'],
            ['GET',    'todos/{id}', 'TodoController', 'show'],
            ['POST',   'todos',      'TodoController', 'store'],
            ['PUT',    'todos/{id}', 'TodoController', 'update'],
            ['DELETE', 'todos/{id}', 'TodoController', 'destroy'],
        ];
    }

    public function dispatch(): void

    {
        if (str_starts_with($this->uri, "todo")) // uri starts with "todo" -> AuthMiddleware prüfen
            $this->authUser = AuthMiddleware::handle(); // Token prüfen und payload zurückgeben

        foreach ($this->routes as [$method, $pattern, $controller, $action]) {

            // {id} im Pattern → in Regex umwandeln
            $regex = get_pattern_ids($pattern);
            #$regex = '#^' . preg_replace('/\{[a-z]+\}/', '([0-9]+)', $pattern) . '$#';
            if ($this->method === $method && preg_match($regex, $this->uri, $matches)) {
                $id = $matches[1] ?? null;
                $ctrl = new $controller();
                // Auth-User dem Controller übergeben
                if (method_exists($ctrl, 'setAuthUser')) {
                    #dd($this->authUser);
                    $ctrl->setAuthUser($this->authUser);
                }
                $ctrl->$action($id);
                return;
            }
        }

        // Keine Route gefunden
        Response::json(['error' => 'Route not found3'], 404);
    }
}