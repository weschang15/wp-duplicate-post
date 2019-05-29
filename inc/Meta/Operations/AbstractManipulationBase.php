<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta\Operations;

/**
 * AbstractManipulationClass - class responsible for sharing common code between Operation handler classes that manipulate
 * the database (i.e. Insert, Upsert) and ensuring that the proper methods required for said handlers are implemented.
 */
abstract class AbstractManipulationBase extends AbstractOperationBase implements ManipulationInterface
{
  /**
   * Post meta key (i.e. identifier) used to attach additional information to specific WP Posts. 
   * 
   * @var string
   */
  protected $meta_key;

  /**
   * Post meta value used to attach additional information to specific WP Posts. 
   *
   * @var mixed
   */
  protected $meta_val;

  /**
   * Function responsible for setting instance member variables which can then be used to insert into the WP Post Meta 
   * database. This function also sanitizes meta key and value parameters.
   *
   * @uses sanitize_key() - prepares metadata name for database
   * @uses sanitize_meta() - prepares metadata value for database
   * 
   * @param string|null $key - metadata name
   * @param mixed|null $value - metadata value associated with name
   * 
   * @return ManipulationInterface $this - class extension (i.e. Insert, Upsert)
   */
  public function meta(string $key = null, $value = null)
  {
    if (is_null($key)) {
      throw new \Error(sprintf('%s must be of type string. Instead received type NULL.', '$key'));
    }

    $meta_key = sanitize_key($key);

    $this->meta_key = $meta_key;
    $this->meta_val = sanitize_meta($meta_key, $value, 'post');

    return $this;
  }
}