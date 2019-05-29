<?php

namespace WesleyChang\WPDP\Api;

use WesleyChang\WPDP\Controllers\Base;

require_once(ABSPATH . 'wp-admin/includes/plugin.php');

class Settings extends Base
{

  public $admin_pages = [];

  public $network_pages = [];

  public $subpages = [];

  public $settings = [];

  public $sections = [];

  public $fields = [];

  public function register()
  {

    if (!empty($this->admin_pages) || !empty($this->subpages)) {
      add_action('admin_menu', [$this, 'add_admin_menu']);
    }

    if (!empty($this->network_pages) && is_plugin_active_for_network($this->plugin) && is_multisite()) {
      add_action('network_admin_menu', [$this, 'add_network_admin_menu']);
    }

    if (!empty($this->settings)) {
      add_action('admin_init', [$this, 'register_custom_fields']);
    }
  }

  public function with_subpage($title = '')
  {

    if (empty($this->admin_pages)) {
      return $this;
    }

    $admin_page = $this->admin_pages[0];

    $subpage = [
      [
        'parent_slug' => $admin_page['menu_slug'],
        'page_title' => $admin_page['page_title'],
        'menu_title' => (!empty($title)) ? $title : $admin_page['menu_title'],
        'capability' => $admin_page['capability'],
        'menu_slug' => $admin_page['menu_slug'],
        'callback' => $admin_page['callback'],
      ]
    ];

    $this->subpages = $subpage;

    return $this;
  }

  public function add_admin_pages($pages = [])
  {

    $this->admin_pages = $pages;

    return $this;
  }

  public function add_network_pages($pages = [])
  {

    $this->network_pages = $pages;

    return $this;
  }

  public function add_subpages($pages = [])
  {

    $this->subpages = \array_merge($this->subpages, $pages);

    return $this;
  }

  public function add_network_admin_menu()
  {

    foreach ($this->network_pages as $page) {

      add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
    }

    foreach ($this->subpages as $page) {

      add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
    }
  }

  public function add_admin_menu()
  {

    foreach ($this->admin_pages as $page) {

      add_menu_page($page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position']);
    }

    foreach ($this->subpages as $page) {

      add_submenu_page($page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback']);
    }
  }

  public function add_settings($settings = [])
  {

    $this->settings = $settings;

    return $this;
  }

  public function add_sections($sections = [])
  {

    $this->sections = $sections;

    return $this;
  }

  public function add_fields($fields = [])
  {

    $this->fields = $fields;

    return $this;
  }

  public function register_custom_fields()
  {

    foreach ($this->settings as $setting) {

      register_setting($setting['option_group'], $setting['option_name'], (isset($setting['callback']) ? $setting['callback'] : ''));
    }

    foreach ($this->sections as $section) {

      add_settings_section($section['id'], $section['title'], (isset($section['callback']) ? $section['callback'] : ''), $section['page']);
    }

    foreach ($this->fields as $field) {

      add_settings_field($field['id'], $field['title'], (isset($field['callback']) ? $field['callback'] : ''), $field['page'], $field['section'], (isset($field['args']) ? $field['args'] : ''));
    }
  }
}
