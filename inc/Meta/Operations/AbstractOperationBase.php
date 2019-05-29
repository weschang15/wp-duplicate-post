<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta\Operations;

/**
 * AbstractOperationBase - class responsible for sharing common code between all Operation handler classes
 */
abstract class AbstractOperationBase implements BaseInterface
{
  /**
   * The ID of the post to run operations on
   *
   * @var integer
   */
  protected $post_id;

  /**
   * The prefix of the metadata name (e.g. prefix_metadata_name)
   *
   * @var string
   */
  protected $prefix;

  /**
   * Function for setting the Post ID to run operations on
   *
   * @param integer $post_id - Specified Post ID
   * 
   * @return BaseInterface $this - class extension (i.e. Select, Insert, Upsert)
   */
  public function setPostId(int $post_id = null)
  {
    if (is_null($post_id)) {
      throw new \Error(sprintf('%s must be of type int. Instead received type NULL.', '$post_id'));
    }

    $this->post_id = $post_id;

    return $this;
  }

  /**
   * Function for setting metadata name prefixes
   *
   * @param string $prefix - Specified metadata prefix
   * 
   * @return BaseInterface $this - class extension (i.e. Select, Insert, Upsert)
   */
  public function setPrefix(string $prefix = null)
  {
    if (!is_string($prefix)) {
      throw new \Error(sprintf('%s must be of type string. Instead received type NULL.', '$prefix'));
    }

    $this->prefix = $prefix;

    return $this;
  }
}