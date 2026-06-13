<?php
declare(strict_types=1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    // Öffentliche Routen — kein Token nötig
    private static array $publicRoutes = [
        'POST auth/login',
        'POST auth/register',
    ];

    public static function handle(string $method, string $uri): ?object
    {
        // Ist die Route öffentlich?
        if (in_array("$method $uri", self::$publicRoutes)) {
            return null; // durchlassen
        }

        // Authorization Header lesen
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        
        if (!str_starts_with($header, 'Bearer ')) { // Authentifizierungstyp Bearer folgende Token zur Authentifizierung verwenden
            Response::json(['error' => 'Token fehlt'], 401);
            exit;
        }

        $token = substr($header, 7); // "Bearer " entfernen

        try {
            // Token prüfen — gibt Payload zurück
            $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));// Signatur zu prüfen
            return $decoded; // enthält user_id, role, exp //Payload

        } catch (ExpiredException $e) {// wenn "exp" in Payload kleiner als time()
            Response::json(['error' => 'Token abgelaufen'], 401);
            exit;
        } catch (Exception $e) {
            Response::json(['error' => 'Token ungültig'], 401);
            exit;
        }
    }
}