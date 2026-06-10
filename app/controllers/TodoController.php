<?php
#declare(strict_types=1);

class TodoController
{
    private Todo $model;

    public function __construct()
    {
        $this->model = new Todo();
    }

    // GET /todos
    public function index(): void
    {
        Response::json($this->model->all());
    }

    // GET /todos/{id}
    public function show(?string $id): void
    {
        $todo = $this->model->find((int) $id);
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

        $id = $this->model->create($data);
        Response::json(['message' => 'Erstellt', 'id' => $id], 201);
    }

    // PUT /todos/{id}
    // PUT /todos/{id}
    public function update(?string $id): void
    {
        $todo = $this->model->find((int) $id);
        if (!$todo) {
            Response::json(['error' => 'Not found'], 404);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $this->model->update((int) $id, $data);
        Response::json(['message' => 'Aktualisiert']);
    }

    // DELETE /todos/{id}
    public function destroy(?string $id): void
    {
        $todo = $this->model->find((int) $id);
        if (!$todo) {
            Response::json(['error' => 'Not found'], 404);
            return;
        }
        $this->model->delete((int) $id);
        Response::json(['message' => 'Gelöscht (Soft Delete)']);
    }
}