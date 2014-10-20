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

echo "	<div id=\"buzzStream\">";

if (isset($_POST['buzzPostContent'])) {
  echo "<br><span style=\"margin-left:12px;font-weight:bold\">New content:</span><br>";

  $object = new buzzObject($_POST['buzzPostContent']);
  $post = buzzPost::createPost($object);
  $post->id = $_POST['postId'];
  $newPost = $buzz->updatePost($post);
  displayBuzzPost($buzz, $newPost);

} elseif (isset($_GET['postId'])) {

  $post = $buzz->getPost($_GET['postId']);
  echo "<div class=\"buzzPost ui-corner-all\">
    		<form method=\"post\">
    			<b>Enter post text:</b><br>
    			<textarea name=\"buzzPostContent\" class=\"ui-corner-all\">{$post->object->originalContent}</textarea><br>
    			<input type=\"hidden\" name=\"postId\" value=\"".htmlentities($_GET['postId'])."\">
    			<input type=\"submit\" value=\"Edit post\">
    		</form>
		</div>";

} else {

  $stream = $buzz->getPosts('@self', '@me');

  // If we got here, the read action succeeded and we can display the posts
  if (count($stream->posts)) {
    foreach ($stream->posts as $post) {
      echo "<div class=\"buzzPost ui-corner-all\">
        <a class=\"person\" href=\"editPost.php?postId=".urlencode($post->id)."\">edit</a>
        post with ID <span style=\"font-weight: bold\">{$post->id}</span>
        <span style=\"font-style: italic\">{$post->title}..</span>
        </div>";
    }
  } else {
    echo "<h2>No posts returned, try following some people?</examples/includes/footer.php";
  }
}

echo "	</div>\n";

include "includes/footer.php";
