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
 * ActivityStream (/atom) Link element representation, an atom link can contain:
 * - rel either null or 'alternate', 'self', 'preview', 'enclosure', etc
 * - href the link
 * - type as links can have a type associated with them, ie 'text/html', 'image/jpeg', etc
 */
class buzzComment {
  public $id;
  public $published;
  public $person;
  public $content;

  public function __construct($id = null, $published = null, $person = null, $content = null) {
    $this->id = $id;
    $this->published = $published;
    $this->person = $person;
    $this->content = $content;
  }

  static public function createComment($content) {
    return new buzzComment(null, null, null, $content);
  }

/**
 * @return the $id
 */
  public function getId() {
    return $this->id;
  }

/**
 * @return the $published
 */
  public function getPublished() {
    return $this->published;
  }

/**
 * @return the $person
 */
  public function getPerson() {
    return $this->person;
  }

/**
 * @return the $content
 */
  public function getContent() {
    return $this->content;
  }

/**
 * @param $id the $id to set
 */
  public function setId($id) {
    $this->id = $id;
  }

/**
 * @param $published the $published to set
 */
  public function setPublished($published) {
    $this->published = $published;
  }

/**
 * @param $person the $person to set
 */
  public function setPerson($person) {
    $this->person = $person;
  }

/**
 * @param $content the $content to set
 */
  public function setContent($content) {
    $this->content = $content;
  }



}