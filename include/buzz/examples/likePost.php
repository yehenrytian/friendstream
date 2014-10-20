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

require_once "includes/header.php";
require_once "includes/displayBuzzPost.php";
require_once '../src/buzz.php';
require_once "includes/createBuzz.php";

$buzz = createBuzz();

if (isset($_GET['postId'])) {
  if ($_GET['like'] == 1) {
    $ret = $buzz->likePost($_GET['postId']);
  } else {
    $ret = $buzz->unlikePost($_GET['postId']);
  }
}

// Fetch the @me/@self feed (posts of the authenticated user)
$stream = $buzz->getPosts('@consumption', '@me');

// If we got here, the read action succeeded and we can display the posts
if (count($stream->posts)) {
  echo "	<div id=\"buzzStream\">";
  foreach ($stream->posts as $post) {
    echo "<div class=\"buzzPost ui-corner-all\">
      [
      <a class=\"person\" href=\"likePost.php?postId=".urlencode($post->id)."&like=1\">like</a> |
      <a class=\"person\" href=\"likePost.php?postId=".urlencode($post->id)."&like=0\">un-like</a>
      ]
      post with ID <span style=\"font-weight: bold\">{$post->id}</span>
      <span style=\"font-style: italic\">{$post->title}..</span>
      </div>";
  }
  echo "	</div>\n";
} else {
  echo "<h2>No posts returned, try following some people?</examples/includes/footer.php";
}

include "includes/footer.php";
