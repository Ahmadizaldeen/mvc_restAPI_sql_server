# 📝 Todo REST API — PHP MVC Backend

Eine modulare REST API in **PHP 8.2** mit MVC-Architektur, JWT-Authentifizierung und Soft Delete.  
Entwickelt als Lernprojekt im Rahmen der Ausbildung zum **Fachinformatiker Anwendungsentwicklung (IHK)**.

---

## 🚀 Features

- ✅ MVC-Architektur (Model, Controller, Core)
- ✅ Zentrale Route-Registry als einzige Quelle für alle Endpunkte (`RouteRegistry`)
- ✅ REST API mit JSON-Responses
- ✅ JWT-Authentifizierung (HS256)
- ✅ User-Ownership — jeder User sieht nur seine eigenen Todos
- ✅ Soft Delete (`deleted_at` Pattern)
- ✅ Passwort-Hashing mit `password_hash()` (bcrypt)
- ✅ Umgebungsvariablen via `.env`
- ✅ CORS-Unterstützung inkl. Preflight-Handling
- ✅ Apache URL-Rewriting (Front Controller)

---

## 🔀 Routing-Architektur

Alle Routen werden zentral in `app/core/RouteRegistry.php` definiert — dort steht pro
Route die HTTP-Methode, das URL-Pattern, der zuständige Controller, die Action und ob
die Route öffentlich oder geschützt ist:

```php
['GET', 'todos/{id}', TodoController::class, 'show', false], // false = Auth erforderlich
```

`Router::dispatch()` liest ausschließlich aus dieser Registry und aktiviert die
`AuthMiddleware` nur für Routen mit `isPublic = false`. Es gibt keine zweite,
unabhängige Routenliste mehr im Projekt — jede neue Route wird an genau einer Stelle
eingetragen.

### Workflow

```
1. POST /auth/register  → User anlegen: body={name, lastname, email, password}
2. POST /auth/login     → Token erstellen: body={email, password} → JWT
3. POST /todos           → Authorization: Bearer <Token>, body={title, description, status}
4. PUT /todos/{id}       → Authorization: Bearer <Token>, body={title, description, status}
5. GET /todos            → eigene Todos abrufen, Authorization: Bearer <Token>
6. DELETE /todos/{id}    → Soft Delete, Authorization: Bearer <Token>

Alle Routen mit isPublic=false erfordern den Authorization-Header.
```

---

## 🌐 CORS

CORS-Header werden zentral über `sendCorsHeaders()` (`include/helpers.php`) gesetzt,
sowohl für normale Requests als auch für den `OPTIONS`-Preflight:

```php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    sendCorsHeaders(includeOptions: true);
    http_response_code(200);
    exit;
}
sendCorsHeaders();
```

Zum manuellen Testen des Preflight-Verhaltens (z. B. mit VS Code Live Server auf einem
anderen Port) liegt `test/cors_test.html` im Projekt bereit.

**Hinweis:** Preflight-`OPTIONS`-Requests werden von aktuellen Chrome-Versionen (ab
Chrome 79) standardmäßig nicht im Network-Tab der Devtools angezeigt. Zur Verifikation
eignen sich Server-Logs oder `curl -X OPTIONS ... -i`.
Daher ein neue Feather ist geplant : Server_loggen

---

## 🔧 Bekannte Einschränkungen / Roadmap

- [ ] User name in Response # `AuthController::Response::json()`
- [ ] `username` beim Register automatisch befüllen
- [ ] `erstellt_am` → `created_at` in `users` vereinheitlichen
- [ ] Token-Blacklist für sofortigen Logout
- [ ] Notes-Modul (`/notes`)
- [ ] Rate Limiting
- [ ] Input-Sanitization Middleware
- [ ] API Request/Response-Logging für Security-Audit (in Planung)

---

## 🗄️ Datenbankschema

```sql
-- todos
id          INT AUTO_INCREMENT PK
user_id     INT UNSIGNED NOT NULL (FK → users.id)
title       VARCHAR(255) NOT NULL
description TEXT
status      ENUM('open','done') DEFAULT 'open'
created_at  DATETIME
updated_at  DATETIME
deleted_at  DATETIME (Soft Delete)

-- users
id          INT UNSIGNED AUTO_INCREMENT PK
name        VARCHAR(50) NOT NULL
lastname    VARCHAR(50) NOT NULL
email       VARCHAR(100) UNIQUE NOT NULL
username    VARCHAR(50) DEFAULT NULL
password    VARCHAR(255) NOT NULL (bcrypt)
role        VARCHAR(20) DEFAULT 'user'
status      ENUM('active','block') DEFAULT 'active'
erstellt_am TIMESTAMP
updated_at  DATETIME
deleted_at  DATETIME (Soft Delete)
```

---

### HTTP Status Codes Übersicht

| Code | Bedeutung |
|---|---|
| `200` | OK — Anfrage erfolgreich |
| `201` | Created — Ressource erstellt |
| `401` | Unauthorized — Token fehlt, abgelaufen oder ungültig |
| `404` | Not Found — Ressource oder Route nicht gefunden |
| `422` | Unprocessable — Validierungsfehler |
| `500` | Internal Server Error — z. B. Datenbankverbindung fehlgeschlagen |

**Fehler nach Endpunkt:**

| Endpunkt | Code | Grund |
|---|---|---|
| `POST /auth/register` | `422` | Pflichtfeld fehlt |
| `POST /auth/login` | `401` | E-Mail oder Passwort falsch |
| `POST /auth/login` | `422` | Pflichtfeld fehlt |
| `GET/PUT/DELETE /todos/{id}` | `404` | Todo nicht gefunden oder gehört anderem User |
| `POST /todos` | `422` | `title` fehlt |
| beliebiger Endpunkt | `500` | Datenbankverbindung fehlgeschlagen |
| unbekannte Route | `404` | Route existiert nicht in der `RouteRegistry` |

---

## 🧪 Testen mit Thunder Client / Postman

Alle Endpunkte lassen sich mit Thunder Client (VS Code) oder Postman testen. Für
CORS-Preflight-Tests siehe Abschnitt „CORS" oben — dafür ist ein echter Browser-Kontext
nötig, kein REST-Client.

---

## 🔒 Sicherheit

| Maßnahme | Implementierung |
|---|---|
| Passwort-Hashing | `password_hash()` mit bcrypt |
| JWT Signierung | HS256 mit geheimem Schlüssel aus `.env` |
| SQL Injection | PDO Prepared Statements überall |
| User-Isolation | `user_id` aus JWT Token — nie aus Request Body |
| Soft Delete | Daten bleiben für Audit-Zwecke erhalten |
| Secrets | Nie im Repository — nur in `.env` |
| Fehlerbehandlung | Keine rohen Exception-Messages im Client-Response |

---

## 🗂️ Projektstruktur

```
mvc_restAPI_sql_server/
├── index.php                        ← Front Controller (Einstiegspunkt)
├── .htaccess                        ← URL-Rewriting + Auth-Header Fix
├── .env                             ← Secrets (nicht im Repo!)
├── .env.example                     ← Vorlage für .env
├── composer.json
│
├── app/
│   ├── controllers/
│   │   ├── AuthController.php       ← register, login
│   │   ├── HomeController.php       ← API-Info auf Root-Route
│   │   └── TodoController.php       ← CRUD für Todos
│   ├── core/
│   │   ├── Database.php             ← PDO Singleton
│   │   ├── Response.php             ← JSON-Antworten
│   │   ├── RouteRegistry.php        ← einzige Quelle aller Routen
│   │   └── Router.php               ← Routing + Middleware-Aufruf
│   ├── middleware/
│   │   └── AuthMiddleware.php       ← JWT prüfen
│   ├── models/
│   │   ├── Todo.php                 ← DB-Operationen für Todos
│   │   └── User.php                 ← DB-Operationen für User
│   └── helpers/
│       └── bootstrap.php            ← BASE_PATH Konstante
│
├── config/
│   └── database.php                 ← DB-Konfiguration
│
├── include/
│   └── helpers.php                  ← dd(), get_pattern_ids(), sendCorsHeaders()
│
├── test/
│   └── cors_test.html               ← manueller CORS-Preflight-Test
│
└── data/SQL/
    ├── migration/
    │   ├── 000_create_tables.sql
    │   ├── 001_create_table_users.sql
    │   └── 002_add_user_id_to_todos.sql
    └── seeder/
        └── 001_users_todos_test_daten.sql
```

---

## 🛠️ Voraussetzungen

| Tool | Version | Download |
|---|---|---|
| **PHP** | 8.2+ | [php.net](https://www.php.net) / XAMPP |
| **MySQL** | 8.0+ | [mysql.com](https://www.mysql.com) / XAMPP |
| **Apache** | 2.4+ | XAMPP / WAMP |
| **Composer** | aktuell | [getcomposer.org](https://getcomposer.org/Composer-Setup.exe) |

### PHP-Erweiterungen (müssen aktiv sein)

In `php.ini` diese Zeilen aktivieren (`;` am Anfang entfernen):

```ini
extension=zip
extension=pdo_mysql
extension=curl
extension=mbstring
```

---

## 📦 Pakete

| Paket | Version | Zweck |
|---|---|---|
| `vlucas/phpdotenv` | ^5.6 | `.env` Datei laden |
| `firebase/php-jwt` | ^7.0 | JWT Token erstellen & prüfen |

---

## ⚙️ Installation

### 1. Repository klonen

```bash
git clone https://github.com/Ahmadizaldeen/mvc_restAPI_sql_server.git
cd mvc_restAPI_sql_server
composer install
```

### 2. `.env` einrichten

```bash
cp .env.example .env
```

`.env` ausfüllen (DB-Zugangsdaten, `JWT_SECRET`, `APP_ENV=local` für Debug-Ausgaben).

### 3. Datenbank einrichten

Migrationen **in dieser Reihenfolge** in phpMyAdmin oder MySQL CLI ausführen:

```bash
# 1. Todos-Tabelle erstellen
data/SQL/migration/000_create_tables.sql

# 2. Users-Tabelle erstellen
data/SQL/migration/001_create_table_users.sql

# 3. user_id zu todos hinzufügen
data/SQL/migration/002_add_user_id_to_todos.sql

# Optional: Testdaten einspielen
data/SQL/seeder/001_users_todos_test_daten.sql
```

### 4. Apache konfigurieren (XAMPP)

Projekt in XAMPP `htdocs` ablegen und sicherstellen, dass `mod_rewrite` aktiv ist:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Und `AllowOverride All` für das `htdocs`-Verzeichnis gesetzt ist.

---

## 🌐 Frontend

PHP Frontend: [todo-frontend-php](https://github.com/Ahmadizaldeen/todo-frontend-php)  
React Frontend: geplant

---

## 👨‍💻 Autor

**Ahmad Izaldeen**  
Fachinformatiker Anwendungsentwicklung (Umschulung)  
[github.com/Ahmadizaldeen](https://github.com/Ahmadizaldeen)

---

## 📄 Lizenz

MIT