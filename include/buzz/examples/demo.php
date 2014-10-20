<?php
require_once '../src/buzz.php';

$storage = new buzzFileStorage('/tmp/buzzCache');

session_start();
$localUserId = session_id();

$auth = buzzOAuth::performOAuthLogin($storage, $localUserId);

$buzz = new buzz($storage, $auth);

// Render the @consumption stream
$stream = $buzz->getPosts('@consumption', '@me', 30, 20, 20);
foreach ($stream->posts as $post) {
  $titleLink = $post->links->alternate[0]->href;
  switch ($post->object->type) {
    case 'note':
      $content = $post->object->content;
  }

  foreach ($post->object->attachments as $attachment) {
    switch ($attachment->type) {
      case 'photo':
        $content .= "create custom html for a photo here";
        break;
      case 'photo-album':
        $content .= "create custom html for a photo album here";
        break;
      case 'video':
        $content .= "create custom html for a video here";
        break;
      case 'article':
        $content .= "create custom html for an article here";
        break;
    }
  }
  echo $post->person->name . " wrote: " . $content;
}

// Create a Google Buzz post with a link attachment
$attachment = new buzzAttachment('article', 'Example title');
$attachment->links = array('alternate' => array(new buzzLink('http://example.org', 'text/html')));
$object->attachments = array($attachment);
$post = buzzPost::createPost($object);
$newPost = $buzz->createPost($post);