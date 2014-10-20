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

/**
 * Root ActivityStream element representation that contains the feed's properties
 * and ActivitySteam entries
 *
 */
class buzzStream {
  public $id;
  public $title;
  public $updated;
  public $links;
  public $posts;

  public function __construct($id = null, $title = null, $updated = null, $links = null, $posts = null) {
    $this->id = $id;
    $this->title = $title;
    $this->updated = $updated;
    $this->links = $links;
    $this->posts = $posts;
  }

  /**
   * @return the $id
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return the $title
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * @return the $updated
   */
  public function getUpdated() {
    return $this->updated;
  }

  /**
   * @return the $links
   */
  public function getLinks() {
    return $this->links;
  }

  /**
   * @return the $posts
   */
  public function getPosts() {
    return $this->posts;
  }

  /**
   * @param $id the $id to set
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @param $title the $title to set
   */
  public function setTitle($title) {
    $this->title = $title;
  }

  /**
   * @param $updated the $updated to set
   */
  public function setUpdated($updated) {
    $this->updated = $updated;
  }

  /**
   * @param $links the $links to set
   */
  public function setLinks($links) {
    $this->links = $links;
  }

  /**
   * @param $posts the $posts to set
   */
  public function setPosts($posts) {
    $this->posts = $posts;
  }
}
