<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta\Operations;

/**
 * ManipulationInterface - blueprint promise for AbstractManipulationBase. Anything that extends AbstractManipulationBase 
 * will inherit it's class methods and member vars.
 */
interface ManipulationInterface
{
  /**
   * Required function for saving post metadata
   *
   * @param integer (optional) $post_id - Specified post ID to run insert operation on
   * @return integer|bool - metadata ID if success, otherwise false
   */
  public function save(int $post_id = null);

  /**
   * Required function responsible for setting instance member variables which can then be used to insert or update metadata. 
   * 
   * @param string|null $key - metadata name
   * @param mixed|null $value - metadata value associated with name
   * 
   * @return ManipulationInterface $this - class extension (i.e. Insert, Upsert)
   */
  public function meta(string $key = null, $value = null);
}