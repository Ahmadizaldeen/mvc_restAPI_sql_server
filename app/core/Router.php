<?php
declare(strict_types=1);

class Router
{
    private string $method;
    private string $uri;
#    private array  $routes = [];
    private ?object $authUser = null;

    public function __construct(string $method, string $uri)
    {
        $this->method = $method;
        $this->uri    = $uri;
    }

    public function dispatch(): void

    {
        foreach (RouteRegistry::ROUTES as [$method, $pattern, $controller, $action, $isPublic]) {
            
            $regex = get_pattern_ids($pattern);// [a-z]+, only lowercase letters, allow numeric IDs ([0-9]+) 

            if ($this->method !== $method || !preg_match($regex, $this->uri, $matches)) {
                continue;// next route if method or pattern does not match
            }

            // Route gefunden -> Auth nur prüfen, wenn NICHT public
            if (!$isPublic) {
                $this->authUser = AuthMiddleware::handle(); // Token prüfen und payload zurückgeben
            }

            $id   = $matches[1] ?? null;
            $ctrl = new $controller();
            if (method_exists($ctrl, 'setAuthUser')) {
                    $ctrl->setAuthUser($this->authUser);
                }
            $ctrl->$action($id);
                return;
        }
        Response::json(['error' => 'Route not found'], 404);
    }
 }