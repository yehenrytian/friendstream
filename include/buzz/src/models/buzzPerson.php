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

class buzzPerson {
  public $id;
  public $name;
  public $profileUrl;
  public $thumbnailUrl;
  public $urls;
  public $photos;
  public $aboutMe;
  public $organizations;
  public $interests;
  public $emails;

  public function __construct($id = null, $name = null, $profileUrl = null, $thumbnailUrl = null, $urls = null, $photos = null, $aboutMe = null, $organizations = null, $interests = null, $emails = null) {
    $this->id = $id;
    $this->name = $name;
    $this->profileUrl = $profileUrl;
    $this->thumbnailUrl = $thumbnailUrl;
    $this->urls = $urls;
    $this->photos = $photos;
    $this->aboutMe = $aboutMe;
    $this->organizations = $organizations;
    $this->interests = $interests;
    $this->emails = $emails;
  }

  /**
   * @return the $id
   */
  /**
   * @return the $emails
   */
  public function getEmails() {
    return $this->emails;
  }

  /**
   * @param $emails the $emails to set
   */
  public function setEmails($emails) {
    $this->emails = $emails;
  }

  public function getId() {
    return $this->id;
  }

  /**
   * @return the $name
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @return the $profileUrl
   */
  public function getProfileUrl() {
    return $this->profileUrl;
  }

  /**
   * @return the $thumbnailUrl
   */
  public function getThumbnailUrl() {
    return $this->thumbnailUrl;
  }

  /**
   * @return the $urls
   */
  public function getUrls() {
    return $this->urls;
  }

  /**
   * @return the $photos
   */
  public function getPhotos() {
    return $this->photos;
  }

  /**
   * @return the $aboutMe
   */
  public function getAboutMe() {
    return $this->aboutMe;
  }

  /**
   * @return the $organizations
   */
  public function getOrganizations() {
    return $this->organizations;
  }

  /**
   * @return the $interests
   */
  public function getInterests() {
    return $this->interests;
  }

  /**
   * @param $id the $id to set
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @param $name the $name to set
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @param $profileUrl the $profileUrl to set
   */
  public function setProfileUrl($profileUrl) {
    $this->profileUrl = $profileUrl;
  }

  /**
   * @param $thumbnailUrl the $thumbnailUrl to set
   */
  public function setThumbnailUrl($thumbnailUrl) {
    $this->thumbnailUrl = $thumbnailUrl;
  }

  /**
   * @param $urls the $urls to set
   */
  public function setUrls($urls) {
    $this->urls = $urls;
  }

  /**
   * @param $photos the $photos to set
   */
  public function setPhotos($photos) {
    $this->photos = $photos;
  }

  /**
   * @param $aboutMe the $aboutMe to set
   */
  public function setAboutMe($aboutMe) {
    $this->aboutMe = $aboutMe;
  }

  /**
   * @param $organizations the $organizations to set
   */
  public function setOrganizations($organizations) {
    $this->organizations = $organizations;
  }

  /**
   * @param $interests the $interests to set
   */
  public function setInterests($interests) {
    $this->interests = $interests;
  }
}
