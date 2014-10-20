<?php

/**
 * See http://code.google.com/apis/accounts/docs/RegistrationForWebAppsAuto.html for
 * information on generating your oauth consumer key and secret
 */
/*
$buzzConfig = array(
  'site_name' => 'friendstream.ca',
  'oauth_consumer_key' => 'firsttimer.ca',
  'oauth_consumer_secret' => '24MWvS5qUjtz+rRM9SNJ+tjW',
  'oauth_rsa_key' => '',

  // Don't change these values unless you know what you're doing
  'base_url' => 'https://www.googleapis.com/buzz/v1',

  /* Google's OAuth end-points */
  /*
  'access_token_url' => 'https://www.google.com/accounts/OAuthGetAccessToken',
  'request_token_url' => 'https://www.google.com/accounts/OAuthGetRequestToken',
  'authorization_token_url' => 'https://www.google.com/buzz/api/auth/OAuthAuthorizeToken?scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fbuzz&domain=firsttimer.ca&iconUrl=http%3A%2F%2Fwww.firsttimer.ca/firsttimer-ico.png&oauth_token=',
  'oauth_scope' => 'https://www.googleapis.com/auth/buzz'
 );*/

$buzzConfig = array(
  'site_name' => 'friendstream.ca',
  'oauth_consumer_key' => 'friendstream.ca',
  'oauth_consumer_secret' => '',
  'oauth_rsa_key' => '',

  // Don't change these values unless you know what you're doing
  'base_url' => 'https://www.googleapis.com/buzz/v1',

  /* Google's OAuth end-points */
  'access_token_url' => 'https://www.google.com/accounts/OAuthGetAccessToken',
  'request_token_url' => 'https://www.google.com/accounts/OAuthGetRequestToken',
  'authorization_token_url' => 'https://www.google.com/buzz/api/auth/OAuthAuthorizeToken?scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fbuzz&domain=friendstream.ca&iconUrl=http%3A%2F%2Ffriendstream.ca/images/fsicon2.png&oauth_token=',
  'oauth_scope' => 'https://www.googleapis.com/auth/buzz'
 );

?>
