<?php
class Request
{
    private $public_request = [
        ['GET', ''], // leere Route.
        ['POST', 'auth/register'],
        ['POST', 'auth/login'],
    ];

    private $private_request = [
        // Todos — geschützt
        ['GET',    'todos'], //index
        ['GET',    'todos/{id}'], // show
        ['POST',   'todos'], // store
        ['PUT',    'todos/{id}'], // update
        ['DELETE', 'todos/{id}'], // delete
    ];
    private string $method;
    private string $uri;

    public function __construct(string $method, string $uri) // 1 request = 1 Objekt
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    public function handle_request() // leer?, private?, public? , notfound = !private & !public
    {
        $method = $this->method;
        $uri = $this->uri;

        $isPublic = false;
        foreach ($this->public_request as $route) {
            if ($route[1] === $uri) {
                $isPublic = true;
                if ($uri === '') {
                    Response::json(["name" => "Todo API", "Version" => "2.1.1", "status" => "running"]); //->exit;
                }
                return;
            }
        }

        $isPrivate = false;
        foreach ($this->private_request as $route) {
            if (str_starts_with($uri, "todo") && $method === $route[0]) {
                $isPrivate = true;
                #dd($isPrivate);
            }
        }

        if (!$isPublic && !$isPrivate) {
            Response::json(['error' => 'Route not found1'], 404);

        } else if (($uri === 'auth/register' || $uri === 'auth/login')) { // Browser Debug
            if ($method === 'GET') { 
                Response::json(['error' => 'Use POST method für login und register'], 401);
                exit;
            }
        } else return;
    }
}
