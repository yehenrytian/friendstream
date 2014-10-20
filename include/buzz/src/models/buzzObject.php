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

class buzzObject {
  public $type;
  public $content;
  public $originalContent;
  public $links;
  public $attachments;

  public function __construct($content = null, $links = null, $attachments = null, $type = 'note', $originalContent = null) {
    $this->type = $type;
    $this->content = $content;
    $this->links = $links;
    $this->attachments = $attachments;
    $this->originalContent = $originalContent;
  }

  /**
   * @return the $type
   */
  /**
 * @return the $originalContent
 */
  public function getOriginalContent() {
    return $this->originalContent;
  }

/**
 * @param $originalContent the $originalContent to set
 */
  public function setOriginalContent($originalContent) {
    $this->originalContent = $originalContent;
  }

public function getType() {
    return $this->type;
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
   * @return the $attachments
   */
  public function getAttachments() {
    return $this->attachments;
  }

  /**
   * @param $type the $type to set
   */
  public function setType($type) {
    $this->type = $type;
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

  /**
   * @param $attachments the $attachments to set
   */
  public function setAttachments($attachments) {
    $this->attachments = $attachments;
  }

}