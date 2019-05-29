<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta\Operations;

/**
 * Select - operation class responsible for simply querying the WP Postmeta database for specific entires
 */
class Select extends AbstractOperationBase
{
  /**
   * Function responsible for retrieving metadata from WP Postmeta table
   *
   * @param string $meta_key - metadata name
   * @param integer $post_id - specific post ID to retieve metadata from
   * @return mixed - value of metadata
   */
  public function meta(string $meta_key = null, int $post_id = null)
  {
    $id = isset($this->post_id) ? $this->post_id : $post_id;

    if (is_null($id)) {
      throw new \Error(sprintf('%s must be of type int. Instead received type NULL.', '$post_id'));
    }

    if (is_null($meta_key)) {
      throw new \Error(sprintf('%s must be of type string. Instead received type NULL.', 'meta_key'));
    }

    return get_post_meta($this->post_id, $meta_key, true);
  }

  /**
   * Determines if the metadata exists
   *
   * @param string $key - metadata name
   * @return boolean - true if exists, otherwise false
   */
  public function exists(string $key = null)
  {
    return $this->has($key);
  }

  /**
   * Determines if the metadata is non existent
   *
   * @param string $key
   * @return boolean - true if not exists, other false
   */
  public function doesNotExist(string $key = null)
  {
    return !$this->has($key);
  }

  /**
   * Private method to perform logic to check if metadata exists
   *
   * @param string $meta_key
   * @return boolean - true if exists, otherwise false
   */
  private function has(string $meta_key = null)
  {
    if (is_null($meta_key)) {
      throw new \Error(sprintf('%s must be of type string. Instead received type NULL.', 'meta_key'));
    }

    return metadata_exists('post', $this->post_id, $meta_key);
  }
}