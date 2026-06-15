<?php
declare(strict_types=1);

class TodoController
{
    private Todo $todo;
    private ?object $authUser = null; // user_id

    public function __construct()
    {
        $this->todo = new Todo();
    }

    // GET /todos
    public function index(): void
    {
        Response::json($this->todo->all($this->userId()));
    }

    // GET /todos/{id}
    public function show(?string $id): void
    {
        $todo = $this->todo->find((int) $id, $this->userId());
        $todo ? Response::json($todo) : Response::json(['error' => 'Not found'], 404);
    }

    // POST /todos
    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);//php:// Stream Wrappers


        if (empty($data['title'])) {
            Response::json(['error' => 'Title ist Pflicht'], 422);
            return; // defensive Programmierung sonst geht die Methode weiter
        }

        $id = $this->todo->create($data, $this->userId());
        Response::json(['message' => 'Erstellt', 'id' => $id], 201);
    }

    // PUT /todos/{id}
    // PUT /todos/{id}
    public function update(?string $id): void
    {
        $todo = $this->todo->find((int) $id, $this->userId());
        if (!$todo) {
            Response::json(['error' => 'Not found'], 404);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $this->todo->update((int) $id, $data, $this->userId());
        Response::json(['message' => 'Aktualisiert']);
    }

    // DELETE /todos/{id}
    public function destroy(?string $id): void
    {
        $todo = $this->todo->find((int) $id, $this->userId());
        if (!$todo) {
            Response::json(['error' => 'Not found'], 404);
            return;
        }
        $this->todo->delete((int) $id, $this->userId());
        Response::json(['message' => 'Gelöscht (Soft Delete)']);
    }

    // Router ruft das auf und übergibt den JWT Payload
    public function setAuthUser(?object $authUser): void
    {
        $this->authUser = $authUser;
    }

    // Hilfsmethode — user_id sauber auslesen
    private function userId(): int
    {
        return (int) $this->authUser->user_id;
    }
}