<?php

namespace WesleyChang\WPDP\Controllers;

class Base
{
  public $plugin_path;

  public $plugin_url;

  public $plugin;

  public $version;

  public $handle;

  public function __construct()
  {
    $this->plugin_path = plugin_dir_path(dirname(__FILE__, 2));
    $this->plugin_url = plugin_dir_url(dirname(__FILE__, 2));
    $this->plugin =
      plugin_basename(dirname(__FILE__, 3)) . '/wp-duplicate-post.php';
    $this->handle = plugin_basename(dirname(__FILE__, 3));
    $this->version = '1.0.0';
  }
}