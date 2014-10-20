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

class buzzAttachment {
  public $type;
  public $title;
  public $content;
  public $links;

  public function __construct($type = null, $title = null, $content = null, $links = null) {
    $this->type = $type;
    $this->title = $title;
    $this->content = $content;
    $this->links = $links;
  }

  /**
   * @return the $type
   */
  public function getType() {
    return $this->type;
  }

  /**
   * @return the $title
   */
  public function getTitle() {
    return $this->title;
  }

  /**
   * @return the $content
   */
  public function getContent() {
    return $this->content;
  }

  /**
   * @return the $links
   */
  public function getLinks() {
    return $this->links;
  }

  /**
   * @param $type the $type to set
   */
  public function setType($type) {
    $this->type = $type;
  }

  /**
   * @param $title the $title to set
   */
  public function setTitle($title) {
    $this->title = $title;
  }

  /**
   * @param $content the $content to set
   */
  public function setContent($content) {
    $this->content = $content;
  }

  /**
   * @param $links the $links to set
   */
  public function setLinks($links) {
    $this->links = $links;
  }

}