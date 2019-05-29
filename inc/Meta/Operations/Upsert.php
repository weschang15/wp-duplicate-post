<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta\Operations;

/**
 * Upsert - Operation handler responsible for updating post metadata into a specific WP Post
 */
class Upsert extends AbstractManipulationBase
{
  /**
   * Required [by ManipulationInterface within AbstractManipulationBase] function for updating 
   * post metadata.
   * 
   * @uses update_post_meta() - WP native function to updating post metadata
   *
   * @param integer (optional) $post_id - Specified post ID to run insert operation on
   * @return integer|boolean - metadata ID if success, otherwise false
   */
  public function save(int $post_id = null)
  {
    $id = isset($this->post_id) ? $this->post_id : $post_id;

    if (is_null($id)) {
      throw new \Error(sprintf('%s must be of type int. Instead received type NULL.', '$post_id'));
    }

    $meta_key = $this->meta_key;
    $meta_val = $this->meta_val;

    return update_post_meta($id, $meta_key, $meta_val);
  }
}