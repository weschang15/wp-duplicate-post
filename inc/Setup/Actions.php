<?php

namespace WesleyChang\WPDP\Setup;

use WesleyChang\WPDP\Controllers\Base;

class Actions extends Base
{
  public function register()
  {
    add_action('admin_init', [$this, 'init_options']);
  }

  public function init_options()
  {
    $default = [];

    if (!get_option('wc_wpdp_options')) {
      update_option('wc_wpdp_options', $default);
    }
  }
}
