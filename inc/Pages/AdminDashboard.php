<?php

namespace WesleyChang\WPDP\Pages;

use WesleyChang\WPDP\Api\Settings;
use WesleyChang\WPDP\Controllers\Base;
use WesleyChang\WPDP\Api\Callbacks\AdminCallbacks;

class AdminDashboard extends Base
{
  public $settings;

  public $callbacks;

  public $pages = [];

  public function register()
  {
    $this->settings = new Settings();

    $this->callbacks = new AdminCallbacks();

    $this->create_pages();

    if (is_main_site()) {
      $this->settings
        ->add_admin_pages($this->pages)
        ->register();
    }
  }

  public function create_pages()
  {
    $this->pages = [
      [
        'page_title' => 'WP Duplicate Post',
        'menu_title' => 'WP Duplicate Post',
        'capability' => 'manage_options',
        'menu_slug' => 'wc_wpdp_admin',
        'callback' => [$this->callbacks, 'admin_page'],
        'icon_url' => 'dashicons-admin-tools',
        'position' => 110
      ]
    ];
  }
}
