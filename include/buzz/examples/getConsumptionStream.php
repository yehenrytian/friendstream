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

//require_once "includes/header.php";
require_once "includes/displayBuzzPost.php";
require_once '../src/buzz.php';
require_once "includes/createBuzz.php";

//include "includes/header.php";

$buzz = createBuzz();

echo '
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="css/examples.css" rel="stylesheet" type="text/css">
</head>
<body>
	<div id="headerDiv" class="ui-dark-widget-header ui-corner-all"> &nbsp; Buzz API Demo <div class="backHome">[ <a href="index.php">Back to index</a> ]</div></div>';


// And fetch the @consumption posts
$stream = $buzz->getPosts('@consumption', '@me', 16, 16, 24);



// If we got here, the read action succeeded and we can display the posts
if (count($stream->posts)) {
  echo "	<div id=\"buzzStream\">";
  foreach ($stream->posts as $post) {
    displayBuzzPost($buzz, $post);
  }
  echo "	</div>\n";
} else {
  echo "<h2>No posts returned, try following some people?</examples/includes/footer.php";
}

include "includes/footer.php";
?>