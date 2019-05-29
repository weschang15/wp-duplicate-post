<?php

namespace WesleyChang\WPDP\Api\Callbacks;

use WesleyChang\WPDP\Controllers\Base;

class AdminCallbacks extends Base
{
  const PLUGIN_OPTIONS = 'wc_wpdp_options';
  const DUPLICATE_POST_OPTIONS = 'duplicate_post';
  const FEATURE_DEFAULTS = 'wc_wpdp_duplicate_post_defaults';

  public function admin_page()
  {
    $excludes = ['attachment'];
    $partials = ['duplicate-post'];
    $post_types = [];

    if (get_option(self::FEATURE_DEFAULTS)) {
      $defaults = get_option(self::FEATURE_DEFAULTS);
      $post_types = $defaults['post_types'];
    } else {
      $all_post_types = get_post_types([
        'public' => true,
        'show_in_menu' => true,
        'exclude_from_search' => false
      ]);

      $post_types = array_filter(
        $all_post_types,
        function ($key) use ($excludes) {
          return !in_array($key, $excludes);
        },
        ARRAY_FILTER_USE_KEY
      );
    }

    $settings = get_option(self::PLUGIN_OPTIONS);
    $duplicate_post_settings = [];

    if (isset($settings)) {
      $duplicate_post_settings = isset($settings[self::DUPLICATE_POST_OPTIONS])
        ? $settings[self::DUPLICATE_POST_OPTIONS]
        : null;
    }

    $panes = \array_map(
      function ($partial) use (
        $post_types,
        $duplicate_post_settings
      ) {
        return \wc_wpdp_load_template(
          "templates/panes/{$partial}-pane.php",
          [
            'post_types' => $post_types,
            'settings' => $duplicate_post_settings
          ],
          false
        );
      },
      $partials
    );

    $data = [
      'tabs' => [
        'pane-1' => 'Duplicate Post'
      ],
      'panes' => $panes
    ];

    return \wc_wpdp_load_template('templates/admin-dashboard.php', $data);
  }

  public function admin_documentation_subpage()
  {
    $partials = [
      'shortcode',
      'cta',
      'typography',
      'component',
      'css-utilities',
      'misc'
    ];

    $panes = \array_map(function ($partial) {
      return \wc_wpdp_load_template(
        "templates/panes/{$partial}-pane.php",
        [],
        false
      );
    }, $partials);

    $data = [
      'tabs' => [
        'pane-1' => 'Shortcodes',
        'pane-2' => 'CTA Guidelines',
        'pane-3' => 'Typography',
        'pane-4' => 'Components',
        'pane-5' => 'CSS Utilities',
        'pane-6' => 'Miscellaneous'
      ],
      'panes' => $panes
    ];

    return \wc_wpdp_load_template('templates/cta-documentation.php', $data);
  }
}