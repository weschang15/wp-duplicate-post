<?php

namespace WesleyChang\WPDP\Setup;

use WesleyChang\WPDP\Controllers\Base;

class Enqueue extends Base
{
  public function register()
  {
    add_action('wp_enqueue_scripts', [$this, 'enqueue']);
    add_action('admin_enqueue_scripts', [$this, 'admin_enqueue']);
  }

  public function enqueue() 
  {}

  public function admin_enqueue()
  {
    $externals = [
      'fonts' =>
      'https://fonts.googleapis.com/css?family=Inconsolata:700|Lato:400,400i,700,900',
    ];

    wp_register_style('admin-fonts', $externals['fonts'], [], null);

    // Dashboard
    wp_register_style(
      "$this->handle-admin-dashboard",
      $this->plugin_url . 'public/css/admin-dashboard.min.css',
      [],
      null
    );

    wp_register_script(
      "$this->handle-admin-tabs-script",
      $this->plugin_url . 'public/js/admin-tabs.min.js',
      [],
      null,
      true
    );
    
    $screen = get_current_screen();

    $has_tabs = function ($screen_id = null) {
      if (!isset($screen_id)) {
        return false;
      }

      $doc_pages = [
        'toplevel_page_wc_wpdp_admin'
      ];

      return in_array($screen_id, $doc_pages);
    };

    if ($has_tabs($screen->id)) {
      wp_enqueue_style('wc-wpdp-admin-fonts');
      wp_enqueue_style("$this->handle-admin-dashboard");
    }
  }
}
