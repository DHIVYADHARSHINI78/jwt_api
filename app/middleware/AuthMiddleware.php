<?php
class AuthMiddleware {
    public static function handle() {
     
        $headers = getallheaders();
        $authHeader = $headers['Authorization'] ?? $headers['authorization'] ?? null;

        if (!$authHeader || !preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            Response::json(['error' => 'Unauthorized: Token missing'], 401);
            exit;
        }

        $token = $matches[1];
        $userData = JWT::validate($token);

        if (!$userData) {
            Response::json(['error' => 'Unauthorized: Invalid or expired token'], 401);
            exit;
        }

      
        $method = $_SERVER['REQUEST_METHOD'];
        if (in_array($method, ['POST', 'PUT', 'PATCH'])) {
            $json = file_get_contents('php://input');
            $GLOBALS['request_data'] = json_decode($json, true);
        }

        return $userData;
    }
}