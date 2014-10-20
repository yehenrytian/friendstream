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
require_once '../src/buzz.php';
require_once "includes/createBuzz.php";
require_once "includes/displayBuzzPost.php";

$buzz = createBuzz();
$searchResult = false;

if (isset($_GET['q'])) {
  $searchResult = $buzz->search($_GET['q']);
}
echo "	<div id=\"buzzStream\">";

echo "<div class=\"buzzPost ui-corner-all\">
	<form action=\"searchBuzz.php\" method=get>
	<div style=\"text-align:center;align:center\">
		<input type=\"text\" name=\"q\" style=\"width:400px\"";
if (isset($_GET['q'])) {
  echo " value=\"{$_GET['q']}\" ";
}
echo "><br>
		<input type=\"submit\" value=\"Search\">
	</div>
	</form>
	</div>";


if (isset($_GET['q'])) {
  echo "<br><span style=\"margin-left:12px;font-weight:bold\">Search results for {$_GET['q']}:</span><br>";
  if (!count($searchResult)) {
    echo "<br><span style=\"margin-left:12px;font-style: italic\">No results found.</span><br>";
  } else {
    foreach ($searchResult->posts as $post) {
      displayBuzzPost($buzz, $post);
    }
  }
}
echo "</div>\n";

include "includes/footer.php";
