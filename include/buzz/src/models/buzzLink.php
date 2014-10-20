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
 * - href the link
 * - type as links can have a type associated with them, ie 'text/html', 'image/jpeg', etc
 */
class buzzLink {
  public $href;
  public $type;
  public $count;
  public $mediaHeight;
  public $mediaWidth;

  public function __construct($href = null, $type = null, $count = null, $mediaHeight = null, $mediaWidth = null) {
    $this->href = $href;
    $this->type = $type;
    $this->count = $count;
    $this->mediaHeight = $mediaHeight;
    $this->mediaWidth = $mediaWidth;
  }

  /**
   * @return string ('http://www.example.org/feed/123')
   */
  public function getHref() {
    return $this->href;
  }

  /**
   * @return string type ('text/html')
   */
  public function getType() {
    return $this->type;
  }

  public function getMediaHeight() {
    return $this->mediaHeight;
  }

  public function getMediaWidth() {
    return $this->mediaWidth;
  }

  public function setMediaHeight($mediaHeight) {
    $this->mediaHeight = $mediaHeight;
  }

  public function setMediaWidth($mediaWidth) {
    $this->mediaWidth = $mediaWidth;
  }

  /**
   * @param string $href
   */
  public function setHref($href) {
    $this->href = $href;
  }

  /**
   * @param string $type
   */
  public function setType($type) {
    $this->type = $type;
  }
}
