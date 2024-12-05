<?php
require __DIR__ . "/../vendor/autoload.php";

class GoogleLogin {
    private $client;

    public function __construct() {
        $this->client = new Google\Client();
        $this->client->setClientId("479293806926-m8afj0p3nea0o45242077nq9rk0kuirb.apps.googleusercontent.com");
        $this->client->setClientSecret("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");
        $this->client->setRedirectUri("http://localhost/sampleRent/login/api.php");
        $this->client->addScope("email");
        $this->client->addScope("profile");
    }

    public function getAuthUrl() {
        return $this->client->createAuthUrl();
    }

    public function authenticate($code) {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        // Check if the token is valid and contains the access token
        if (array_key_exists('error', $token)) {
            throw new Exception("Error fetching access token: " . $token['error']);
        }
        // Set the access token
        $this->client->setAccessToken($token['access_token']);
        return $token; // Return the full token array for further use if needed
    }

    public function getUserInfo() {
        $oauth2 = new Google\Service\Oauth2($this->client);
        return $oauth2->userinfo->get();
    }
}
?>
