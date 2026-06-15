USE phpd_mvc_rest;

-- Benutzer
INSERT INTO users (
    name,
    lastname,
    email,
    username,
    password,
    role
) VALUES
(
    'Ahmad',
    'Izaldeen',
    'ahmad@example.com',
    'ahmad',
    '$2y$10$abcdefghijklmnopqrstuv12345678901234567890123456789',
    'admin'
),
(
    'Max',
    'Mustermann',
    'max@example.com',
    'max',
    '$2y$10$abcdefghijklmnopqrstuv12345678901234567890123456789',
    'user'
),
(
    'Anna',
    'Schmidt',
    'anna@example.com',
    'anna',
    '$2y$10$abcdefghijklmnopqrstuv12345678901234567890123456789',
    'user'
);

-- Todos
INSERT INTO todos (
    title,
    description,
    status,
    user_id
) VALUES
(
    'PHP MVC Projekt erstellen',
    'Grundstruktur mit Router und Controller aufbauen',
    'open',
    1
),
(
    'JWT Authentication',
    'Login und Token-Erstellung implementieren',
    'done',
    1
),
(
    'Frontend verbinden',
    'API über Fetch ansprechen',
    'open',
    2
),
(
    'Repository Pattern',
    'UserRepository und TodoRepository erstellen',
    'open',
    2
),
(
    'Dokumentation schreiben',
    'REST API dokumentieren',
    'done',
    3
);