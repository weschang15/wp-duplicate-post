<?php

namespace WesleyChang\WPDP\Helpers;

class Utils
{
  /**
   * Function to determine if a post exists by a given ID.
   * `get_post_status` will return a string value of the post status or a bool of false if the post doesn't exist.
   *
   * Since we want a return value of true or false, we wrap the function in a `is_string` to check the return value of
   * `get_post_status`
   *
   * @param int|null $id - The ID of the post to check
   * @return bool - True if the post exists; otherwise, false
   */
  public static function post_exists($id = null)
  {
    if (!isset($id)) {
      throw new \Exception("Missing parameter of type integer");
    }

    return is_string(get_post_status($id));
  }
}