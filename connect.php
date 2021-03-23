<?php
require 'vendor/autoload.php';
// to enable the use of the .env
if (file_exists(__DIR__ . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// exchanging the access code for an access token
$tokenEndpoint = 'https://discord.com/api/oauth2/token';
$redirectURI = 'http://localhost/Oauth2-Discord-Curl/connect.php';

$curl = curl_init();

try {
$data = array(
    'client_id' => $_ENV['CLIENT_ID'],
    'client_secret'=> $_ENV['CLIENT_SECRET'],
    'grant_type'=> 'authorization_code',
    'code'=> $_GET['code'],
    'redirect_uri'=> $redirectURI,
    'scope'=> urlencode('identify email'));

// alternative: curl_setopt($curl, CURLOPT_URL, $tokenEndpoint); (set options one by one)
$options = array(
    CURLOPT_URL => $tokenEndpoint,
    CURLOPT_POST => true, 
    CURLOPT_POSTFIELDS => $data,
    CURLOPT_HTTPHEADER => array('Content-Type' => 'application/x-www-form-urlencoded'),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => false,
    CURLOPT_FAILONERROR    => true,
);

curl_setopt_array($curl, $options);

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo curl_error($curl);
    die();
}

$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

// if the exchange is successful, calling the API endpoint with the access token
if($http_code == intval(200)){
    curl_close($curl);
    $curl2 = curl_init();
    $accessToken = json_decode($response)->access_token;
    $options = array(
        CURLOPT_URL => 'https://discord.com/api/v8/users/@me',
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('Authorization: Bearer ' . $accessToken),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER         => false,
        CURLOPT_FAILONERROR    => true,
    );
    curl_setopt_array($curl2, $options);
    
    $response = curl_exec($curl2);
    if (curl_errno($curl2)) {
        echo curl_error($curl2);
        die();
    }
    $http_code = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
    if($http_code == intval(200)) {
        $username = json_decode($response)->username;
        session_start();
        $_SESSION['username'] = $username;
        curl_close($curl2);
        header('Location: http://localhost/Oauth2-Discord-Curl/');
    } else {
        echo 'Resource not found: ' . $http_code;
    }
} else {
    echo 'Resource not found: ' . $http_code;
}

} catch(\Throwable $th) {
    throw $th;
} finally {
    curl_close($curl);
};





