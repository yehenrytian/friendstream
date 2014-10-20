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
 * Buzz Entry element representation
 */
class buzzPost {
  public $id;
  public $title;
  public $published;
  public $updated;
  public $person;
  public $links;
  public $sourceTitle;
  public $geoPoint;
  public $address;
  public $verb;
  public $visibility;
  public $object;
  public $comments;
  public $liked;

  function __construct($id = null, $title = null, $published = null, $updated = null, $person = null, $links = null, $sourceTitle = null, $geoPoint = null, $address = null, $verb = null, $visibility = null, $object = null, $comments = null, $liked = null) {
    $this->id = $id;
    $this->title = $title;
    $this->published = $published;
    $this->updated = $updated;
    $this->person = $person;
    $this->links = $links;
    $this->sourceTitle = $sourceTitle;
    $this->geoPoint = $geoPoint;
    $this->address = $address;
    $this->verb = $verb;
    $this->visibility = $visibility;
    $this->object = $object;
    $this->liked = $liked;
    $this->comments = $comments;
  }

  /**
   * Factory method to simplify creation of buzz posts
   * @param string $title title of the post
   * @param buzzObject $object the activity object to create
   * @param string $geoPoint in 'long, lat' format
   * @param string $verb verb to use, right now this should always be 'post'
   */
  static public function createPost(buzzObject $object, $geoPoint = null, $verb = 'post') {
    return new buzzPost(null, null, null, null, null, null, null, $geoPoint, null, $verb, null, $object);
  }

  /**
 * @return the $address
 */
  public function getAddress() {
    return $this->address;
  }

/**
 * @param $address the $address to set
 */
  public function setAddress($address) {
    $this->address = $address;
  }

/**
 * @param $visibility the $visibility to set
 */
  public function setVisibility($visibility) {
    $this->visibility = $visibility;
  }

public function getVisibility() {
    return $this->visibility;
  }

  /**
   * @return the activity:verb element
   */
  public function getVerb() {
    return $this->verb;
  }

  /**
   * @return buzzObject the activity:object element
   */
  public function getObject() {
    return $this->object;
  }


  /**
   * @return buzzPerson the person element
   */
  public function getPerson() {
    return $this->person;
  }

  public function getSourceTitle() {
    return $this->sourceTitle;
  }

  /**
   * @return id of this activity
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return Array link(s) associated with this activity
   */
  public function getLinks() {
    return $this->links;
  }

  /**
   * @return string published date
   */
  public function getPublished() {
    return $this->published;
  }

  /**
   * @return string updated date
   */
  public function getUpdated() {
    return $this->updated;
  }

  /**
   * @return string title of this activity
   */
  public function getTitle() {
    return $this->title;
  }

  public function getGeoPoint() {
    return $this->geoPoint;
  }

  public function setVisivility($visibility) {
    $this->visibility = $visibility;
  }

  /**
   * @param string $verb
   */
  public function setVerb($verb) {
    $this->verb = $verb;
  }

  /**
   * @param buzzObject $object
   */
  public function setObject(buzzObject $object) {
    $this->object = $object;
  }

  /**
   * @param buzzPerson $person
   */
  public function setPerson(buzzPerson $person) {
    $this->person = $person;
  }

  public function setSourceTitle($sourceTitle) {
    $this->sourceTitle = $sourceTitle;
  }

  /**
   *
   * @param string$point
   */
  public function setGeoPoint($point) {
    $this->geoPoint = $point;
  }

  /**
   * @param string $id
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @param Array $links
   */
  public function setLinks(Array $links) {
    $this->links = $links;
  }

  /**
   * @param asLink $link link to add to the links property
   */
  public function addLink(buzzLink $link) {
    $this->links[] = $link;
  }

  /**
   * @param string $published
   */
  public function setPublished($published) {
    $this->published = $published;
  }

  /**
   * @param string $updated
   */
  public function setUpdated($updated) {
    $this->updated = $updated;
  }

  /**
   * @param string $title
   */
  public function setTitle($title) {
    $this->title = $title;
  }
}
