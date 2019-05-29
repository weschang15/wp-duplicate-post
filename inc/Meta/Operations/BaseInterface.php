<?php
/**
 * Author: Wesley Chang
 */
namespace WesleyChang\WPDP\Meta\Operations;

/**
 * BaseInterface - blueprint promise for AbstractOperationBase. Anything that extends AbstractOperationBase 
 * will inherit it's class methods and member vars.
 */
interface BaseInterface
{
  /**
   * Required function for setting a post ID to run operations on
   *
   * @param integer $post_id
   * @return BaseInterface
   */
  public function setPostId(int $post_id = null);

  /**
   * Required function for setting a metadata name prefix
   *
   * @param string $prefix
   * @return BaseInterface
   */
  public function setPrefix(string $prefix = null);
}