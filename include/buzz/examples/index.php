<!--<?php// require_once "includes/header.php"; ?>-->
<?php require_once ('/www/110mb.com/t/i/a/n/y/e/_/_/tianye/htdocs/include/jsonwrapper.php'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <link href="css/examples.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="headerDiv" class="ui-dark-widget-header ui-corner-all"> &nbsp; Buzz API Demo <div class="backHome">[ <a href="index.php">Back to index</a> ]</div></div>
<div id="buzzStream">
	<div class="left" style="float:left; width:49%">
  		<div class="buzzPost ui-corner-all" style="width:98%">
  			<a class="person" href="getConsumptionStream.php">Get the consumption stream</a>
  		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
  			<a class="person" href="getPublicStream.php">Get the public stream</a>
  		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="editPost.php">Edit a post</a>
		</div>
 		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="likePost.php">(un)Like a post</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="deletePost.php">Delete a post</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="createPostSimple.php">Create a post</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="createPostLink.php">Create a post with a link</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="createPostMedia.php">Create a post with a photo attachment.</a>
		</div>
	</div>
	<div class="right" style="float:right; width:49%">
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="addOrEditComment.php">Add or Edit comments</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="followPerson.php">Follow someone.</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="unfollowPerson.php">Stop following someone.</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="searchBuzz.php">Search for Buzz Posts.</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="searchPeople.php">Search for people.</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="getFollowing.php">Get following.</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="getFollows.php">Get follows</a>
		</div>
  		<div class="buzzPost ui-corner-all" style="width:98%">
			<a class="person" href="getGroups.php">Get groups</a>
		</div>
	</div>
</div>
<?php require_once "includes/footer.php"; ?>