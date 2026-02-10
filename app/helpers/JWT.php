<?php
class JWT {

    private static $secret = "dhivya_123"; 

    public static function generate($payload) {
   
    $payload['iat'] = time();
    $payload['exp'] = time() + 3600; 

    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $base64Header = self::base64UrlEncode($header);
    $base64Payload = self::base64UrlEncode(json_encode($payload));

    $signature = hash_hmac('sha256', "$base64Header.$base64Payload", self::$secret, true);
    $base64Signature = self::base64UrlEncode($signature);

    return "$base64Header.$base64Payload.$base64Signature";
}

    public static function validate($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return false;

        list($header, $payload, $signature) = $parts;
        $validSig = self::base64UrlEncode(hash_hmac('sha256', "$header.$payload", self::$secret, true));

        if ($signature !== $validSig) return false;

        $data = json_decode(base64_decode($payload), true);
        
  
        if (!isset($data['exp']) || $data['exp'] < time()) {
            return false;
        }

        return $data;
    }

    private static function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }
}