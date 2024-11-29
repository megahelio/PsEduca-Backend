<?php

use exception\ValidationException;

class JWT
{
    /**
     * Headers for JWT.
     *
     * @var array
     */
    private $headers;

    /**
     * Secret for JWT.
     *
     * @var string
     */
    private $secret;

    public function __construct()
    {
        $this->headers = [
            'alg' => 'HS256', // we are using a SHA256 algorithm
            'typ' => 'JWT', // JWT type
            'iss' => SERVER_URL, // token issuer
        ];
        $this->secret = JWT_KEY; // change this to your secret code
    }

    /**
     * Generate JWT using a payload.
     *
     * @param array $payload
     * @return string
     */
    public function generate(array $payload): string
    {
        $headers = $this->encode(json_encode($this->headers)); // encode headers
        $payload["exp"] = time() + (86400 * 30); // add expiration to payload
        $payload["iss"] = SERVER_URL; // add issuer to payload
        $payload = $this->encode(json_encode($payload)); // encode payload
        $signature = hash_hmac('SHA256', "$headers.$payload", $this->secret, true); // create SHA256 signature
        $signature = $this->encode($signature); // encode signature

        return "$headers.$payload.$signature";
    }

    /**
     * Encode JWT using base64.
     *
     * @param string $str
     * @return string
     */
    private function encode(string $str): string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '='); // base64 encode string
    }

    /**
     * Check if JWT is valid, return payload if valid.
     *
     * @param string $jwt
     * @return array the payload
     * @throws ValidationException
     */
    public function decode(string $jwt): array
    {
        $token = explode('.', $jwt); // explode token based on JWT breaks
        if (!isset($token[1]) && !isset($token[2])) {
            throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
        }
        $headers = base64_decode($token[0]); // decode header, create variable
        $payload = base64_decode($token[1]); // decode payload, create variable
        $clientSignature = $token[2]; // create variable for signature

        if (!json_decode($payload)) {
            throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
        }

        if ((json_decode($payload)->exp - time()) < 0) {
            throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
        }

        if (!isset(json_decode($payload)->sub)) {
            throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
        }

        if (isset(json_decode($payload)->iss) && isset(json_decode($headers)->iss)) {
            if (json_decode($headers)->iss != json_decode($payload)->iss) {
                throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
            }
        } else {
            throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
        }

//        if (isset(json_decode($payload)->aud)) {
//            if (json_decode($headers)->aud != json_decode($payload)->aud) {
//                return false; // fails if audiences are not the same
//            }
//        } else {
//            return false; // fails if audience is not set
//        }

        $base64_header = $this->encode($headers);
        $base64_payload = $this->encode($payload);

        $signature = hash_hmac('SHA256', $base64_header . "." . $base64_payload, $this->secret, true);
        $base64_signature = $this->encode($signature);

        if ($base64_signature !== $clientSignature) {
            throw new ValidationException(array(ResponseCodes::AUTHENTICATION_INVALID_KO));
        }

        return json_decode($payload, associative: true);
    }
}
