<?php
/*
 * Copyright 2008 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Shared code that creates a Buzz object and authenticates using OAuth
 */
function createBuzz() {
  try {
    // Use file based storage for the demno, on production systems you would want to use Apc or MySql based storage
    $storage = new buzzFileStorage('/tmp/buzzCache');

    // Create a local user ID based on a session ID (normally you would use some form of authentication to get to a user id, this is for demo only)
    session_start();
    $localUserId = session_id();

    // calling performOAuthLogin will do the oauth dance for you (redirecting to google.com where the user is prompted to grant access)
    $auth = buzzOAuth::performOAuthLogin($storage, $localUserId);

    // Create the actual Buzz object with our Storage and Auth classes
    $buzz = new buzz($storage, $auth);
    return $buzz;

  } catch (buzzException $e) {
    die("<b>Fatal error:</b> ".$e->getMessage());
  }
}

?>