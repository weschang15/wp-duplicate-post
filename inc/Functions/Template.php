<?php

if (!function_exists('wc_wpdp_load_template')) {
  function wc_wpdp_load_template($path, $args = [], $echo = true)
  {
    $file = plugin_dir_path(dirname(__FILE__, 2)) . $path;

    if (!empty($args)) {
      extract($args);
    }

    if ($echo) {
      require_once $file;
      return;
    }

    ob_start();

    require_once $file;

    return ob_get_clean();
  }
}