<?php
//require_once ('../include/google-api-php/src/apiClient.php');
//require_once ('../include/google-api-php/src/contrib/apiPlusService.php');
require_once ('../include/google-api-php-client/src/Google_Client.php');
require_once ('../include/google-api-php-client/src/contrib/Google_PlusService.php');


session_start();

if (!isset($_SESSION['gplusobj'])){
$client = new Google_Client();
$client->setApplicationName("Google+ PHP Friendstream WebApplication");

//*********** Replace with Your API Credentials **************
$client->setClientId('');
$client->setClientSecret('');
$client->setRedirectUri('http://www.friendstream.ca/friendstream');
$client->setDeveloperKey('');
//************************************************************
 
$client->setScopes(array('https://www.googleapis.com/auth/plus.login'));

// get the sign-in google plus oauth URL
$authUrl = $client->createAuthUrl();
}

if (isset($_GET['code'])) {
  //$plus = new apiPlusService($client);
  $client->authenticate();
  $_SESSION['gp_access_token'] = $client->getAccessToken();
  
  $_SESSION['gplusobj'] = 1;
  header ("Location: http://friendstream.ca");
  //header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}


//if (isset($_SESSION['gp_access_token'])) {
  //$client->setAccessToken($_SESSION['gp_access_token']);
//}


?>
