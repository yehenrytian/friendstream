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
$newPost = false;

if (isset($_POST['buzzPostContent']) && isset($_POST['buzzPostMediaEnclosure']) && isset($_POST['buzzPostMediaPreview']) && isset($_POST['buzzPostMediaAlternate']) && isset($_POST['buzzPostMediaTitle']) && ! empty($_POST['buzzPostContent']) && ! empty($_POST['buzzPostMediaEnclosure']) && ! empty($_POST['buzzPostMediaPreview']) && ! empty($_POST['buzzPostMediaAlternate']) && ! empty($_POST['buzzPostMediaTitle'])) {

  $object = new buzzObject($_POST['buzzPostContent']);

  $attachment = new buzzAttachment('photo', $_POST['buzzPostMediaTitle']);

  if (strpos($_POST['buzzPostMediaEnclosure'], 'png')) {
    $mediaEnclosureType = 'image/png';
  } elseif (strpos($_POST['buzzPostMediaEnclosure'], 'gif')) {
    $mediaEnclosureType = 'image/gif';
  } else {
    $mediaEnclosureType = 'image/jpeg';
  }
  if (strpos($_POST['buzzPostMediaPreview'], 'png')) {
    $mediaPrevieweType = 'image/png';
  } elseif (strpos($_POST['buzzPostMediaPreview'], 'gif')) {
    $mediaPreviewType = 'image/gif';
  } else {
    $mediaPreviewType = 'image/jpeg';
  }

  $attachment->links = array(
     'alternate' => array(new buzzLink($_POST['buzzPostMediaAlternate'], 'text/html')),
     'enclosure' => array(new buzzLink($_POST['buzzPostMediaEnclosure'], $mediaEnclosureType)),
     'preview' => array(new buzzLink($_POST['buzzPostMediaPreview'], $mediaPreviewType)));

  $object->attachments = array($attachment);

  $post = buzzPost::createPost($object);

  $newPost = $buzz->createPost($post);

} elseif (count($_POST)) {
  echo "<span class=\"error\">All fields are required</span>";
}

echo "	<div id=\"buzzStream\">
		<div class=\"buzzPost ui-corner-all\">
    		<form method=\"post\">
    			<b>Enter post text:</b><br>
    			<textarea name=\"buzzPostContent\" class=\"ui-corner-all\"></textarea><br>
    			<b>Photo url:</b> (http://example.org/myimage.jpg)<br>
    			<input name=\"buzzPostMediaEnclosure\" class=\"input\"><br>
    			<b>Preview url:</b> (http://example.org/myimage_thumb.jpg)<br>
    			<input name=\"buzzPostMediaPreview\" class=\"input\"><br>
    			<b>Containing page:</b> (http://example.org/my.html)<br>
    			<input name=\"buzzPostMediaAlternate\" class=\"input\"><br>
    			<b>Photo title:</b> (My image)<br>
    			<input name=\"buzzPostMediaTitle\" class=\"input\"><br>
    			<input type=\"submit\" value=\"Create post\">
    		</form>
		</div>";
if ($newPost) {
  echo "<br><span style=\"margin-left:12px; font-weight:bold\">New post has been created:</span><br/>";
  displayBuzzPost($buzz, $newPost);
}
echo "</div>\n";

include "includes/footer.php";
