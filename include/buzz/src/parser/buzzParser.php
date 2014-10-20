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

require_once "models/buzzStream.php";
require_once "models/buzzLink.php";
require_once "models/buzzPost.php";
require_once "models/buzzComment.php";
require_once "models/buzzPerson.php";
require_once "models/buzzAttachment.php";
require_once "models/buzzObject.php";
require_once "models/buzzTarget.php";

/**
 * ActivityStrea.ms parser class
 *
 */
class buzzParser {

  /**
   * Parses the Buzz stream & returns a activityStream object
   *
   * @param string $feedText
   * @return ActivityStream object
   */
  static public function parse($feedText) {
    $decoded = json_decode($feedText, true);
    if (empty($decoded) || !isset($decoded['data'])) {
      throw new buzzException("Invalid stream format");
    }
    $decoded = $decoded['data'];
    $stream = self::parseFeed($decoded);
    $stream = self::trimResponse($stream);
    return $stream;
  }

  /**
   * Parses the atom array into internal object representation
   * @param array $stream
   */
  public static function parseFeed($stream) {
    // parse the entries
    $entries = null;
    if (isset($stream['items']) && count($stream['items'])) {
      $entries = array();
      foreach ($stream['items'] as $entry) {
        $entries[] = self::parseEntry($entry);
      }
    }

    $links = null;
    if (isset($stream['links']) && count($stream['links'])) {
      $links = array();
      foreach ($stream['links'] as $linkName => $linkValue) {
        if (!isset($links[$linkName])) $links[$linkName] = array();
        foreach ($linkValue as $linkValueEntry) {
          $links[$linkName][] = self::parseLink($linkValueEntry);
        }
      }
    }

    // & create the activitystream object
    $activityStream = new buzzStream(
      isset($stream['id']) ? $stream['id'] : null,
      isset($stream['title']) ? $stream['title'] : null,
      isset($stream['updated']) ? $stream['updated'] : null,
      $links,
      $entries
    );
    return $activityStream;
  }

  public static function parseEntry($data) {
    if (!count($data)) {
      return false;
    }
    $person = null;
    if (isset($data['actor'])) {
      $person = self::parsePerson($data['actor']);
    }
    $links = null;
    if (isset($data['links'])  && count($data['links'])) {
      $links = array();
      foreach ($data['links'] as $linkName => $linkValue) {
        if (!isset($links[$linkName])) $links[$linkName] = array();
        foreach ($linkValue as $linkValueEntry) {
          $links[$linkName][] = self::parseLink($linkValueEntry);
        }
      }
    }
    $object = null;
    if (isset($data['object'])) {
      $objectLinks = null;
      if (isset($data['object']['links'])  && count($data['object']['links'])) {
        $objectLinks = array();
        foreach ($data['object']['links'] as $linkName => $linkValue) {
          if (!isset($links[$linkName])) $links[$linkName] = array();
          foreach ($linkValue as $linkValueEntry) {
            $objectLinks[$linkName][] = self::parseLink($linkValueEntry);
          }
        }
      }
      $attachments = null;
      if (isset($data['object']['attachments']) && count($data['object']['attachments'])) {
        $attachments = array();
        foreach ($data['object']['attachments'] as $attachment) {
          $attachmentLinks = null;
          if (isset($attachment['links'])  && count($attachment['links'])) {
            $attachmentLinks = array();
            foreach ($attachment['links'] as $aLinkName => $aLinkValue) {
              if (!isset($links[$aLinkName])) $links[$aLinkName] = array();
              foreach ($aLinkValue as $aLinkValueEntry) {
                $attachmentLinks[$aLinkName][] = self::parseLink($aLinkValueEntry);
              }
            }
          }
          $attachments[] = new buzzAttachment(
            isset($attachment['type']) ? $attachment['type'] : null,
            isset($attachment['title']) ? $attachment['title'] : null,
            isset($attachment['content']) ? html_entity_decode($attachment['content']) : null,
            $attachmentLinks
          );
        }
      }
      $object = new buzzObject(
        isset($data['object']['content']) ? $data['object']['content'] : null,
        $objectLinks,
        $attachments,
        isset($data['object']['type']) ? $data['object']['type'] : null,
        isset($data['object']['originalContent']) ? $data['object']['originalContent'] : null
      );
    }
    $visibility = null;
    if (isset($data['visibility']['entries']) && count($data['visibility']['entries'])) {
      $visibility = array();
      foreach ($data['visibility']['entries'] as $visibilityEntry) {
        $visibility[] = array('id' => $visibilityEntry['id'], 'title' => isset($visibilityEntry['title']) ? $visibilityEntry['title'] : null);
      }
    }

    $comments = null;
    if (isset($data['object']['comments'])) {
      $comments = array();
      foreach ($data['object']['comments'] as $comment) {
        $comments[] = buzzParser::parseComment($comment);
      }
    }

    $liked = null;
    if (isset($data['object']['liked'])) {
      $liked = array();
      foreach ($data['object']['liked'] as $like) {
        $liked[] = buzzParser::parsePerson($like);
      }
    }

    $entry = new buzzPost(
      isset($data['id']) ? $data['id'] : null,
      isset($data['title']) ? html_entity_decode($data['title']) : null,
      isset($data['published']) ? $data['published'] : null,
      isset($data['updated']) ? $data['updated'] : null,
      $person,
      $links,
      isset($data['source']['title']) ? $data['source']['title'] : null,
      isset($data['geocode']) ? $data['geocode'] : null,
      isset($data['address']) ? $data['address'] : null,
      isset($data['verbs'][0]) ? $data['verbs'][0] : null,
      $visibility,
      $object,
      $comments,
      $liked
    );
    return $entry;
  }

  static public function parseComments($feedText) {
    $decoded = json_decode($feedText, true);
    if (empty($decoded) || !isset($decoded['data'])) {
      throw new buzzException("Invalid stream format: ".print_r($decoded, true));
    }
    $comments = array();
    if (isset($decoded['data']['items']) && count($decoded['data']['items'])) {
      $decoded = $decoded['data']['items'];
      foreach ($decoded as $comment) {
        $comments[] = self::parseComment($comment);
      }
      $comments = self::trimResponse($comments);
    }
    return $comments;
  }

  static public function parseComment($comment) {
    return new buzzComment(
          isset($comment['id']) ? $comment['id'] : null,
          isset($comment['published']) ? $comment['published'] : null,
          isset($comment['actor']) ? self::parsePerson($comment['actor']) : null,
          isset($comment['content']) ? $comment['content'] : null
        );
  }

  static public function parseFollowing($feedText) {
    $decoded = json_decode($feedText, true);
    $following = array();
    if (isset($decoded['data']['entry'])) {
      foreach ($decoded['data']['entry'] as $person) {
        $following[] = self::parsePerson($person);
      }
    }
    return $following;
  }

  static public function parsePeople($feedText) {
    $decoded = json_decode($feedText, true);
    if (empty($decoded) || !isset($decoded['data'])) {
      throw new buzzException("Invalid stream format");
    }
    $ret = array();
    $decoded = $decoded['data'];
    if (isset($decoded['entry']) && count($decoded['entry'])) {
      foreach ($decoded['entry'] as $like) {
        $ret[] = self::parsePerson($like);
      }
    }
    $ret = self::trimResponse($ret);
    return $ret;
  }

  static public function parsePerson($person) {
    if (isset($person['id'])) {
      $id = $person['id'];
    } else {
      $id = isset($person['profileUrl']) ? substr($person['profileUrl'], strrpos($person['profileUrl'], '/') + 1) : null;
    }
    return new buzzPerson(
      $id,
      isset($person['name']) ? $person['name'] : (isset($person['displayName']) ? $person['displayName'] : null),
      isset($person['profileUrl']) ? $person['profileUrl'] : null,
      isset($person['thumbnailUrl']) ? $person['thumbnailUrl'] : null,
      isset($person['urls']) ? $person['urls'] : null,
      isset($person['photos']) ? $person['photos'] : null,
      isset($person['aboutMe']) ? $person['aboutMe'] : null,
      isset($person['organizations']) ? $person['organizations'] : null,
      isset($person['interests']) ? $person['interests'] : null,
      isset($person['emails']) ? $person['emails'] : null
    );

  }

  static public function parseLink($link) {
    return new buzzLink(
      isset($link['href']) ? $link['href'] : null,
      isset($link['type']) ? $link['type'] : null,
      isset($link['count']) ? $link['count'] : null,
      isset($link['mediaHeight']) ? $link['mediaHeight'] : null,
      isset($link['mediaWidth']) ? $link['mediaWidth'] : null
    );
  }

  /**
   * Remove all null entries from the object recursively, there's a lot of
   * optional fields and including them all would cost quite a bit of bandwidth
   * when transmitted.
   *
   * @param mixed $object
   * @return mixed
   */
  static public function trimResponse(&$object) {
    if (is_array($object)) {
      foreach ($object as $key => $val) {
        if ($val === null) {
          unset($object[$key]);
        } elseif (is_array($val) || is_object($val)) {
          $object[$key] = self::trimResponse($val);
        }
      }
    } elseif (is_object($object)) {
      $vars = get_object_vars($object);
      foreach ($vars as $key => $val) {
        if ($val === null) {
          unset($object->$key);
        } elseif (is_array($val) || is_object($val)) {
          $object->$key = self::trimResponse($val);
        }
      }
    }
    return $object;
  }

  static public function jsonFormat($json) {
    $tab = "  ";
    $new_json = "";
    $indent_level = 0;
    $in_string = false;
    $json_obj = json_decode($json);
    if (! $json_obj) {
      return false;
    }
    $json = json_encode($json_obj);
    $len = strlen($json);
    for ($c = 0; $c < $len; $c ++) {
      $char = $json[$c];
      switch ($char) {
        case '{':
        case '[':
          if (! $in_string) {
            $new_json .= $char . "\n" . str_repeat($tab, $indent_level + 1);
            $indent_level ++;
          } else {
            $new_json .= $char;
          }
          break;

        case '}':
        case ']':
          if (! $in_string) {
            $indent_level --;
            $new_json .= "\n" . str_repeat($tab, $indent_level) . $char;
          } else {
            $new_json .= $char;
          }
          break;

        case ',':
          if (! $in_string) {
            $new_json .= ",\n" . str_repeat($tab, $indent_level);
          } else {
            $new_json .= $char;
          }
          break;

        case ':':
          if (! $in_string) {
            $new_json .= ": ";
          } else {
            $new_json .= $char;
          }
          break;

        case '"':
          $in_string = ! $in_string;

        default:
          $new_json .= $char;
          break;
      }
    }
    return $new_json;
  }
}
?>