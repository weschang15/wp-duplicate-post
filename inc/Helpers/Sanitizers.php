<?php

namespace WesleyChang\WPDP\Helpers;

class Sanitizers
{
  public static function sanitize_url_raw($url, $encode = false)
  {
    // Check if the user-defined URL has HTTPS enabled
    if (!preg_match("@^https://@", $url)) {
      $url = preg_replace("/^http:/i", "https:", $url);
    }

    $sanitized_url = \filter_var($url, FILTER_SANITIZE_URL);

    if ($encode) {
      return \urlencode($sanitized_url);
    }

    // Only allow valid characters into url
    return $sanitized_url;
  }

  public static function sanitize_string_raw($str, $encode = false)
  {
    $unencoded_str = \filter_var($str, FILTER_SANITIZE_STRING, [
      'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
    ]);

    if ($encode) {
      return \urlencode(\html_entity_decode($unencoded_str, ENT_QUOTES));
    }

    return $unencoded_str;
  }

  public static function sanitize_str($val = null, $req = null, $param = null)
  {
    return sanitize_text_field($val);
  }

  public static function sanitize_int($val = null, $req = null, $param = null)
  {
    return absint(sanitize_text_field($val));
  }

  public static function sanitize_array($val = null, $req = null, $param = null)
  {
    return array_map('sanitize_text_field', wp_unslash($val));
  }
}