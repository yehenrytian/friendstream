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

require_once '../src/buzz.php';
require_once "includes/header.php";
require_once "includes/displayBuzzPost.php";
require_once "includes/createBuzz.php";

$buzz = createBuzz();

if (isset($_GET['person'])) {
  $person = $buzz->getPerson($_GET['person']);
  $buzz->follow($_GET['person']);
  echo "<br><span style=\"margin-left:12px;font-weight:bold\">Now following {$person->name}</span><br>";
}


//FIXME: this should really use suggested users to find people to follow, but since that's not yet available..
$following = $buzz->following('@me');
$suggested = array();
foreach ($following as $key => $person) {
  $following[$person->id] = $person;
  unset($following[$key]);
}
$stream = $buzz->getPosts('@consumption', '@me');
foreach ($stream->posts as $post) {
  if (!isset($following[$post->person->id])) {
    $suggested[$post->person->id] = $post->person;
  }
  $comments = $buzz->getComments($post->person->id, $post->id);
  foreach ($comments as $comment) {
    if (!isset($following[$comment->person->id])) {
      $suggested[$comment->person->id] = $comment->person;
    }
  }
}

echo "	<div id=\"buzzStream\">";
foreach ($suggested as $person) {
  echo "<div class=\"buzzPost ui-corner-all\">
			[ <a class=\"person\" href=\"followPerson.php?person={$person->id}\">follow</a> ]
			 {$person->name}
  		</div>";
}

echo "  </div>";
include "includes/footer.php";