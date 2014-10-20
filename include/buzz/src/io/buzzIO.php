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

class buzzIO {

  const USER_AGENT = 'buzz-php-client v0.1';

  /**
   * Misc function that prefixes the url with the Buzz API url and sign it using
   * with the proper authentication
   *
   * @param string $url the requested URL
   * @param string $method the HTTP verb to use
   * @param string $postBody the optional POST body to send in the request
   * @param boolean $headers whether or not to return header information
   */
  static public function call($url, $storage, $auth, $method = 'GET', $postBody = false, $headers = array(), $baseUrl = null) {
    global $buzzConfig;
    if (!$baseUrl) {
      $baseUrl = $buzzConfig['base_url'] . '/activities';
    }
    if (! $headers) $headers = array();
    $params = array();
    $urlComponents = parse_url($url);
    if (isset($urlComponents['query']) && ! empty($urlComponents['query'])) {
      parse_str($urlComponents['query'], $params);
    }
    $params['alt'] = 'json';
    $headers[] = 'Content-Type: application/json';
    $url = $baseUrl . $url;
    $signedUrl = $auth->sign($method, $url, $params, $postBody, $headers);
    $data = self::send($signedUrl, $method, $postBody, $headers);
    if ($data['http_code'] !== 200) {
      if (substr($data['data'], 0, 1) == '{') {
        $data['data'] = json_decode($data['data'], true);
      }
      echo "[http error]<br><b>URL:</b>$url<br><b>Signed URL:</b>$signedUrl<br><pre>" . print_r($data, true) . "\n</pre>";
    }
    return $data;
  }

  /**
   * Sends a request using the supplied parameters.
   *
   * @param string $url the requested URL
   * @param string $method the HTTP verb to use
   * @param string $postBody the optional POST body to send in the request
   * @param boolean $headers whether or not to return header information
   * @param string $ua the user agent to send in the request
   * @return array the returned data, parsed headers, and status code
   */
  static public function send($url, $method, $postBody = false, $headers = false, $ua = self::USER_AGENT) {
    $ch = curl_init();
	if (!is_string($url)) $url = $url->__toString();
    curl_setopt($ch, CURLOPT_URL, $url);
    if ($postBody) {
      curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
    }
    if ($headers && is_array($headers)) {
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    // We need to set method even when we don't have a $postBody 'DELETE'
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $ua);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($ch, CURLOPT_FAILONERROR, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $data = @curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $errno = @curl_errno($ch);
    $error = @curl_error($ch);
    @curl_close($ch);
    if ($errno != CURLE_OK) {
      throw new buzzException("HTTP Error: " . $error);
    }
    list($raw_response_headers, $response_body) = explode("\r\n\r\n", $data, 2);
    $response_header_lines = explode("\r\n", $raw_response_headers);
    array_shift($response_header_lines);
    $response_headers = array();
    foreach ($response_header_lines as $header_line) {
      list($header, $value) = explode(': ', $header_line, 2);
      if (isset($response_header_array[$header])) {
        $response_header_array[$header] .= "\n" . $value;
      } else {
        $response_header_array[$header] = $value;
      }
    }
    return array('http_code' => $http_code, 'headers' => $response_header_array,
        'data' => $response_body);
  }
}

?>