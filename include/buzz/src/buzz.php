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


// hack around with the include paths a bit so the library 'just works'
$cwd = dirname(__FILE__);
//set_include_path("$cwd/:".get_include_path());
set_include_path("$cwd".PATH_SEPARATOR.":".get_include_path());
//set_include_path(dirname(__FILE__));


require_once "config.php";
if (file_exists($cwd . '/local_config.php')) {
  @require_once($cwd . '/local_config.php');
}
require_once "auth/buzzAuth.php";
require_once "storage/buzzStorage.php";
require_once "io/buzzIO.php";
require_once "parser/buzzParser.php";

class buzzException extends Exception {}
class buzzAuthenticationException extends buzzException {}
class buzzStorageException extends buzzException {}

/**
 * Docs go here
 *
 */
class buzz {

  private static $validFeedTypes = array('@self', '@public', '@consumption', '@liked');
  public $auth;
  public $storage;

  public function __construct(buzzStorage $storage, $auth = null) {
    $this->storage = $storage;
    $this->auth($auth ? $auth : new buzzAuthNone());
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function search($query = null, $geo = null) {
    if (empty($query) && empty($geo)) throw new buzzException("Either query string or geo coordinates required in search()");
    $url = '/search';
    if ($query) {
      $url .= '?q=' . urlencode($query);
    }
    if ($geo) {
      $url .= ($query ? '&' : '?') . 'geocode=' . urlencode($geo);
    }
    $url .=  (strpos($url, '?') ? '&' : '?') . "max-results=100";
    return buzzParser::parse($this->call($url));
  }

  public function getPosts($type, $userId = '@me', $maxComments = false, $maxLiked = false, $maxResults = false) {
    if ($userId == '@me' && (! $this->auth || $this->auth instanceof buzzAuthNone)) throw new buzzException("getPosts() requires either a user id (other then @me) or authentication");
    if (! in_array($type, self::$validFeedTypes)) throw new buzzException("Invalid feed type specified (" . urlencode($type) . "), should be one of" . implode(',', self::$validFeedTypes));
    $queryStr = '/' . $userId . '/' . $type ;
    if ($maxResults) {
      $queryStr .= (strpos($queryStr, '?') ? '&' : '?') . 'max-results=' . $maxResults;
    }
    if ($maxLiked) {
      $queryStr .= (strpos($queryStr, '?') ? '&' : '?') . 'max-liked=' . $maxLiked;
    }
    if ($maxComments) {
      $queryStr .= (strpos($queryStr, '?') ? '&' : '?') . 'max-comments=' . $maxComments;
    }
    $ret = $this->call($queryStr);
    return buzzParser::parse($ret);
  }

  public function getPost($postId) {
    if (empty($postId)) throw new buzzException("getPost postId can not be empty");
    $ret = json_decode($this->call('/@me/@self/' . $postId), true);
    $parsed = buzzParser::parseEntry($ret['data']);
    return $parsed;
  }

  private function editOrCreatePost(buzzPost $post) {
    if (! isset($post->object->content) || empty($post->object->content)) {
      throw new buzzException("createPost requires an object with content set");
    }
    if ($post->object->type != 'note') {
      throw new buzzException("createPost currently only supports note object types");
    }
    // Refactor our verb simplification back to the Buzz expected format
    if (isset($post->verb)) {
      $post->verbs = array($post->verb);
      unset($post->verb);
    }
    $post = array('data' => $post);
    $post = buzzParser::trimResponse($post);
    $post = json_encode($post);
    $method = isset($post->id) && !empty($post->id) ? 'PUT' : 'POST';
    $url = '/@me/@self' . (isset($post->id) && !empty($post->id) ? '/' . $post->id  : '');
    $ret = buzzIO::call($url, $this->storage, $this->auth, $method, $post);
    if ($ret['http_code'] == 200) {
      $ret = json_decode($ret->{'data'}, true);
      $parsed = buzzParser::parseEntry($ret->{'data'});
      return buzzParser::trimResponse($parsed);
    }
    throw new buzzException("Invalid response code (" . $ret['http_code'] . ")");
  }

  public function createPost(buzzPost $post) {
    return $this->editOrCreatePost($post);
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function updatePost(buzzPost $post) {
    return $this->editOrCreatePost($post);
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function deletePost($postId) {
    $ret = buzzIO::call('/@me/@self/' . $postId, $this->storage, $this->auth, 'DELETE');
    return $ret['http_code'] == 200;
  }

  /**
   * Returns the people who liked the post $postId
   * @param string $postId post id to retrieve likers for
   */
  public function getLikes($userId, $postId) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("getLikes() requires authentication");
    $ret = $this->call('/' . $userId . '/@self/' . $postId . '/@liked');
    return buzzParser::parsePeople($ret);
  }

  /**
   * Requires authentication, will throw an BuzzRequiresAuthentication exception if not
   */
  public function likedPosts($user = null) {
    return array();
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function likePost($postId) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("likePost() requires authentication");
    $ret = buzzIO::call('/@me/@liked/' . $postId, $this->storage, $this->auth, 'PUT', "{'data':{'noop':'noop'}}");
    if ($ret['http_code'] == 200) {
      return true;
    }
    throw new buzzException("Invalid response code (" . $ret['http_code'] . ")");
  }

  public function unlikePost($postId) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("likePost() requires authentication");
    $ret = buzzIO::call('/@me/@liked/' . $postId, $this->storage, $this->auth, 'DELETE');
    if ($ret['http_code'] == 200) {
      return true;
    }
    throw new buzzException("Invalid response code (" . $ret['http_code'] . ")");
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   * only returns muted posts for the authenticated user
   */
  public function mutedPosts() {
    return array();
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function mutePost($postId) {
    return null; // or throw exception
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function unmutePost($postId) {
    return null; // or throw exception
  }

  /**
   * Requires authentication
   */
  public function followers($userId) {
    //if (user.private) throw exception
    //if no followers return empty array()
    if ((! $this->auth || $this->auth instanceof buzzAuthNone) && $userId == '@me') throw new buzzException("@me requires authentication");
    $ret = $this->callPeople('/' . $userId . '/@groups/@followers');
    return buzzParser::parseFollowing($ret);
  }

  /**
   * Requires authentication
   * Never throws an exception, but will return an empty array if the people following are set to private
   */
  public function following($userId) {
    if ((! $this->auth || $this->auth instanceof buzzAuthNone) && $userId == '@me') throw new buzzException("@me requires authentication");
    $ret = $this->callPeople('/' . $userId . '/@groups/@following');
    //FIXME probably can do a parsePeople since this is a poco response..
    return buzzParser::parseFollowing($ret);
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function follow($userId) {
    $ret = $this->callPeople('/@me/@groups/@following/'.$userId, 'PUT',  "{'data':{'noop':'noop'}}");
    return $ret['http_code'] == 200;
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function unfollow($userId) {
    $ret = $this->callPeople('/@me/@groups/@following/'.$userId, 'DELETE');
    return $ret['http_code'] == 200;
  }

  /**
   * Requires authentication
   */
  public function getComments($userId, $postId) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("getComments() requires authentication");
    $ret = $this->call('/' . $userId . '/@public/' . $postId . '/@comments');
    return buzzParser::parseComments($ret);
  }

  public function getComment($postId, $commentId) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("getComment() requires authentication");
    $ret = $this->call('/@me/@self/' . $postId . '/@comments/' . $commentId);
    return buzzParser::parseComments($ret);

  }

  private function createOrEditComment($postId, buzzComment $comment, $commentId = null) {
    if (! isset($comment->content) || empty($comment->content)) {
      throw new buzzException("create comment requires content set");
    }
    if (empty($postId)) {
      throw new buzzException("create comment requires a (valid) post id");
    }
    $post = array('data' => $comment);
    $post = buzzParser::trimResponse($post);
    $post = json_encode($post);
    $method = isset($commentId) && !empty($commentId) ? 'PUT' : 'POST';
    $url = '/@me/@self/' . $postId. '/@comments' . (isset($commentId) && !empty($commentId) ? '/' . $commentId : '');
    $ret = buzzIO::call($url, $this->storage, $this->auth, $method, $post);
    if ($ret['http_code'] == 200) {
      $ret = json_decode($ret['data'], true);
      $parsed = buzzParser::parseComment($ret['data']);
      return buzzParser::trimResponse($parsed);
    }
    throw new buzzException("Invalid response code (" . $ret['http_code'] . ")");
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function createComment($postId, buzzComment $comment) {
    return $this->createOrEditComment($postId, $comment);
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function updateComment($postId, buzzComment $comment, $commentId) {
    return $this->createOrEditComment($postId, $comment, $commentId);
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function deleteComment($postId, $commentId) {
    $ret = buzzIO::call('/@me/@self/' . $postId . '/@comments/' . $commentId, $this->storage, $this->auth, 'DELETE');
    return $ret['http_code'] == 200;
  }

  public function searchPeople($query) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("searchPeople requires authentication");
    $ret = $this->callPeople('/search?q='.urlencode($query));
    return buzzParser::parsePeople($ret);
  }

  public function getPerson($userId) {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("getPerson requires authentication");
    $ret = $this->callPeople('/' . $userId . '/@self');
    $decoded = json_decode($ret, true);
    $parsed = buzzParser::parsePerson($decoded['data']);
    return buzzParser::trimResponse($parsed);
  }


  public function suggestedUsers() {
    return array();
  }

  public function getGroups() {
    if (! $this->auth || $this->auth instanceof buzzAuthNone) throw new buzzException("getGroups requires authentication");
    $ret = $this->callPeople('/@me/@groups');
    return json_decode($ret);
    //return buzzParser::parseGroups($ret);
  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function createGroup($userId, $group) {

  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function updateGroup($userId, $group) {

  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function deleteGroup($userId, $group) {

  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function getGroupMembers($userId, $groupId) {

  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function createGroupMember($userId, $groupId, $user) {

  }

  /**
   * Different return values / behaviors based on authenticated / non authenticated usage
   */
  public function deleteGroupMember($userId, $groupId, $user) {

  }

  /**
   * Requires authentication
   */
  public function blocked($userId) {//if (user.private) throw exception
// if no followers return empty array()
  }

  public function block($userId) {

  }

  public function unblock($userId) {

  }

  public function isBlocked($userId) {

  }

  public function reportActivity($activity) {

  }

  public function reportUser($user) {

  }

  public function auth($auth) {
    $this->auth = $auth;
  }

  private function call($url, $method = 'GET') {
    $ret = buzzIO::call($url, $this->storage, $this->auth, $method);
    if ($ret['http_code'] == 200) {
      return $ret['data'];
    }
    throw new buzzException("Invalid response code (" . $ret['http_code'] . ")");
  }

  private function callPeople($url, $method = 'GET', $postBody = null) {
    global $buzzConfig;
    $ret = buzzIO::call($url, $this->storage, $this->auth, $method, $postBody, array(), $buzzConfig['base_url'] . '/people');
    if ($ret['http_code'] == 200) {
      return $ret['data'];
    }
    throw new buzzException("Invalid response code (" . $ret['http_code'] . ")");
  }
}

?>