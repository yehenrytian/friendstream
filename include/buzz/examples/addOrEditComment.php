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
$self = $buzz->getPerson('@me');
$selfId = $self->id;

echo "	<div id=\"buzzStream\">";

if (isset($_GET['postId']) && isset($_GET['commentId']) && $_GET['commentId'] && isset($_GET['delete']) && $_GET['delete'] == 1) {

  $buzz->deleteComment($_GET['postId'], $_GET['commentId']);
  echo "<br><span style=\"margin-left:12px;font-weight:bold\">Comment with ID {$_GET['commentId']} has been deleted</span><br>";

} elseif (isset($_POST['buzzCommentContent'])) {
  echo "<br><span style=\"margin-left:12px;font-weight:bold\">New content:</span><br>";

  $comment = buzzComment::createComment($_POST['buzzCommentContent']);

  if (isset($_POST['commentId']) && $_POST['commentId']) {
    $comment = $buzz->updateComment($_POST['postId'], $comment, $_POST['commentId']);
  } else {
    $comment = $buzz->createComment($_POST['postId'], $comment);
  }

  $post = $buzz->getPost($_POST['postId']);
  displayBuzzPost($buzz, $post);

} elseif (isset($_GET['postId']) && isset($_GET['commentId']) && $_GET['commentId']) {
  //FIXME this should work but at the moment it doesn't, so working around the problem..
  //FIXME $comment = $buzz->getComment($_GET['postId'], $_GET['commentId']);
  $post = $buzz->getPost($_GET['postId']);
  $comments = $buzz->getComments($post->person->id, $post->id);
  $comment = false;
  foreach ($comments as $commentEntry) {
    if ($commentEntry->id == $_GET['commentId']) {
      $comment = $commentEntry;
      break;
    }
  }
  if (!$comment) {
    die("Comment not found, aborting");
  }
  if ($comment->person->id != $selfId) {
    die("You don't own that comment, shoo!");
  }
  echo "<div class=\"buzzPost ui-corner-all\">
    		<form method=\"post\">
    			<b>Edit comment text:</b><br>
    			<textarea name=\"buzzCommentContent\" class=\"ui-corner-all\">{$comment->content}</textarea><br>
    			<input type=\"hidden\" name=\"postId\" value=\"" . htmlentities($_GET['postId']) . "\">
    			<input type=\"hidden\" name=\"commentId\" value=\"" . htmlentities($_GET['commentId']) . "\">
    			<input type=\"submit\" value=\"Edit comment\">
    		</form>
		</div>";

} elseif (isset($_GET['postId'])) {
    echo "<div class=\"buzzPost ui-corner-all\">
    		<form method=\"post\">
    			<b>Add comment text:</b><br>
    			<textarea name=\"buzzCommentContent\" class=\"ui-corner-all\"></textarea><br>
    			<input type=\"hidden\" name=\"postId\" value=\"" . htmlentities($_GET['postId']) . "\">
    			<input type=\"hidden\" name=\"commentId\" value=\"0\">
    			<input type=\"submit\" value=\"Add comment\">
    		</form>
		</div>";
} else {

  $stream = $buzz->getPosts('@self', '@me');

  // If we got here, the read action succeeded and we can display the posts
  if (count($stream->posts)) {
    foreach ($stream->posts as $post) {
      echo "<div class=\"buzzPost ui-corner-all\">
        <a class=\"person\" href=\"addOrEditComment?postId=" . urlencode($post->id) . "&commentId=\">add comment</a> to post with ID <span style=\"font-weight: bold\">{$post->id}</span>
        <span style=\"font-style: italic\">{$post->object->content}..</span>
        ";
      $comments = $buzz->getComments($post->person->id, $post->id);
      if (count($comments)) {
        echo "<div class=\"content\">";
        foreach ($comments as $comment) {
          if ($comment->person->id == $selfId) {
            echo "
            	<div class=\"comment\"> [
                	<a href=\"addOrEditComment.php?postId=".urlencode($post->id)."&commentId=".urlencode($comment->id)."\">edit</a> |
                	<a href=\"addOrEditComment.php?postId=".urlencode($post->id)."&commentId=".urlencode($comment->id)."&delete=1\">delete</a>
                    ] {$comment->content}
                </div>";
          }
        }
        echo "</div>";
      }

      echo "  </div>";
    }
  } else {
    echo "<h2>No posts returned, try following some people?</examples/includes/footer.php";
  }
}

echo "	</div>\n";

include "includes/footer.php";
