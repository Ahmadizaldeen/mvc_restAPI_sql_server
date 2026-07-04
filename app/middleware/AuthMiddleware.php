<?php
declare(strict_types=1);

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;

class AuthMiddleware
{
    public static function handle(): ?object
    {
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