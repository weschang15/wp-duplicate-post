<?php

/**
 * Plugin Name: WP Duplicate Post
 * Plugin URI: https://wesleychang.me
 * Description: Not your average custom plugin... Contains custom shortcodes, share functionality, etc.
 * Version: 1.0.0
 * Author: Wesley Chang
 * Author URI: https://wesleychang.me
 * License: GPLv2 or later
 * Text Domain: wp-duplicate-post
 */

defined("ABSPATH") or die("Hey, you don't belong here!");

if (file_exists(dirname(__FILE__) . "/vendor/autoload.php")) {
  require_once dirname(__FILE__) . "/vendor/autoload.php";
}

function activate_wc_wpdp_plugin()
{
  WesleyChang\WPDP\Setup\Activate::activate();
}

register_activation_hook(__FILE__, 'activate_wc_wpdp_plugin');

if (class_exists("WesleyChang\\WPDP\\Init")) {
  WesleyChang\WPDP\Init::register_services();
}