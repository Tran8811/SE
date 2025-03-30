<?php

session_start();
require_once '../vendor/autoload.php'; // Cần thư viện Google API Client

$clientID = '846625336484-7k4abkaere2r3fnthre3q2hkcaeut0fg.apps.googleusercontent.com';  // Thay bằng Client ID của bạn
$clientSecret = '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';
$redirectUri = 'http://localhost:8080/cupid-again/php/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
exit();

?>
