<?php

namespace WesleyChang\WPDP\Controllers;

use WesleyChang\WPDP\Helpers\Utils;
use WesleyChang\WPDP\Helpers\Sanitizers;

class DuplicatePost extends Base
{
  const PLUGIN_OPTIONS = 'wc_wpdp_options';
  const DUPLICATE_POST_OPTIONS = 'duplicate_post';
  const ACTION_KEY = 'duplicate_post_as_draft';
  const NONCE_KEY = 'duplicate_post_nonce';

  // Options KEY for standalone wp_options record to store feature settings and defaults
  const FEATURE_DEFAULTS = 'wc_wpdp_duplicate_post_defaults';

  /**
   * Storage for defining all default settings (i.e. allowed post_types) this will be updated on every admin_init load
   * to keep allowed post types up-to-date
   *
   * @var array
   */
  private $defaults;

  private $plugin_options;

  private $feature_options;

  private $is_active;

  public function register()
  {
    $this->defaults = [];

    $this->plugin_options = get_option(self::PLUGIN_OPTIONS);
    $this->feature_options = empty($this->plugin_options)
      ? null
      : $this->plugin_options[self::DUPLICATE_POST_OPTIONS];
    $this->is_active = empty($this->feature_options)
      ? false
      : $this->feature_options['status'];

    add_action('admin_enqueue_scripts', [$this, 'enqueue']);

    // Trigger action hook to initialize our plugin feature default settings
    // This needs to run on admin_init so that our settings (in particular, default post_types) are always up-to-date.
    if ($this->is_active) {
      add_filter('post_row_actions', [$this, 'duplicate_link'], 10, 2);
      add_filter('page_row_actions', [$this, 'duplicate_link'], 10, 2);
      add_action('admin_init', [$this, 'set_options']);
      add_action('admin_action_duplicate_post_as_draft', [$this, 'duplicate']);
    }
  }

  public function duplicate()
  {
    global $wpdb;
    $nonce = $_GET[self::NONCE_KEY];

    if (empty($nonce) || !wp_verify_nonce($nonce, $this->handle)) {
      wp_die();
    }

    $post_id = Sanitizers::sanitize_int($_GET['post']);

    if (empty($post_id)) {
      wp_die('Something went wrong, a post ID was not provided.');
    }

    if (!Utils::post_exists($post_id)) {
      wp_die('Something went wrong!');
    }

    // Try to retieve a valid post
    $post = get_post($post_id);
    if (!isset($post)) {
      wp_die("Something went wrong!");
    }

    // We want to make we are able to retrieve a valid post that can be duplicated
    if (isset($post) && is_a($post, 'WP_Post')) {
      // Retrieve the current USER ID so that we can assign our new post ownership
      $user = get_current_user_id();

      $args = [
        'post_author' => $user,
        'post_content' => $post->post_content,
        'post_title' => "Copy of {$post->post_title}",
        'post_excerpt' => $post->post_excerpt,
        'post_name' => "Copy of {$post->post_title}",
        'post_parent' => $post->post_parent,
        'post_password' => $post->post_password,
        'post_status' => 'draft',
        'post_type' => $post->post_type,
        'to_ping' => $post->to_ping,
        'menu_order' => $post->menu_order
      ];

      // insert new post using wp_insert_post
      $new_post_id = wp_insert_post($args);

      // Setup our redirects for future use
      $redirects = [
        'failed' => admin_url(
          sprintf('edit.php?post_type=%s', $post->post_type)
        ),
        'success' => admin_url(
          sprintf('post.php?action=edit&post=%s', $new_post_id)
        )
      ];

      // This will return all of the taxonomy types (i.e. post_tag, category)
      $all_taxonomies = get_object_taxonomies($post);

      // I only want to support the standard WP taxonomies so let's filter out any taxonomy
      // that isn't WP native
      $taxonomies = array_filter($all_taxonomies, function ($tax) {
        return in_array($tax, ['post_tag', 'category']);
      });

      // We run a network! So we want the network database prefix
      $table = $wpdb->base_prefix . 'postmeta';

      // Use WP prepared statements to create properly escaped SQL statements
      $stmt = $wpdb->prepare(
        "INSERT INTO {$table} (meta_key, meta_value, post_id) SELECT meta_key, meta_value, %d AS post_id FROM {$table} WHERE post_id = %d",
        $new_post_id,
        $post_id
      );

      $results = $wpdb->get_results($stmt);
      $error = $wpdb->last_error;
      $success = empty($error);

      // Builds a named associative array that contains all taxonomy IDs assigned to the new post
      $term_ids = array_map(function ($taxonomy) use ($post_id, $new_post_id) {
        $terms = wp_get_object_terms($post_id, $taxonomy, ['fields' => 'ids']);
        return [
          $taxonomy => wp_set_object_terms($new_post_id, $terms, $taxonomy)
        ];
      }, $taxonomies);

      // Let's make sure that if our main query errors out, we delete the newly generated post_type post
      // We could also check if $term_ids is a WP_Error however, I'd argue it's a better experience to just
      // destroy the newly created post only if the main query errored out
      if (!$success) {
        // Force a permanent deletion removing all post meta and associations
        wp_delete_post($new_post_id, true);
        wp_redirect($redirects['failed']);
        exit();
      }

      // If we've gotten to this point, let's redirect to the newly created post edit page
      wp_redirect($redirects['success']);
      exit();
    }
  }

  /**
   * Ensure that the feature options have been initialized with defaults
   *
   * @return void
   */
  public function set_options()
  {
    // Attempt to  retrieve our feature default settings so that we can compare against our post types query
    $defaults = get_option(self::FEATURE_DEFAULTS);

    // Retrieve all post types for the given instance and filter down to only publicly accessible types
    $types = get_post_types([
      'public' => true,
      'show_in_menu' => true,
      'exclude_from_search' => false
    ]);

    // Remove all post types that we don't want to support duplication for
    $post_types = array_keys(
      array_filter(
        $types,
        function ($key) {
          return !in_array($key, ['attachment']);
        },
        ARRAY_FILTER_USE_KEY
      )
    );

    $this->defaults = [
      'post_types' => $post_types
    ];

    // We don't want to ALWAYS update our feature defaults option unless
    // we know that new post types have been added/deleted
    if (isset($defaults) && $post_types === $defaults['post_types']) {
      return;
    }

    update_option(self::FEATURE_DEFAULTS, $this->defaults);
  }

  public function enqueue($hook)
  {
    $is_dashboard = function ($screen_id = null) {
      if (!isset($screen_id)) {
        return false;
      }

      $pages = ['toplevel_page_wc_wpdp_admin'];

      return in_array($screen_id, $pages);
    };

    $is_admin_archive = function ($screen_id = null) {
      if (!isset($screen_id)) {
        return false;
      }

      return $screen_id === "edit.php" &&
        in_array($_GET['post_type'], $this->defaults['post_types']);
    };

    if ($is_dashboard($hook)) {
      wp_enqueue_script(
        "$this->handle-admin-duplicate-post",
        $this->plugin_url . 'public/js/admin-duplicate-post.min.js',
        [],
        null,
        true
      );
    }

    if ($this->is_active && $is_admin_archive($hook)) {
      wp_enqueue_script(
        "$this->handle-admin-duplicate-post",
        $this->plugin_url . 'public/js/admin-duplicate-post.min.js',
        [],
        null,
        true
      );
    }
  }

  public function duplicate_link($actions, $post)
  {
    $return = $actions;

    $post_type = $post->post_type;
    $post_types = $this->feature_options['post_types'];

    if (current_user_can('editor') && in_array($post_type, $post_types)) {
      $link = sprintf(
        '<a href="%4$s" title="Duplicate %2$s" data-post-id="%3$s" data-post-title="%2$s" data-post-handler="%4$s">Duplicate</a>',
        'javascript:void(0)',
        $post->post_title,
        $post->ID,
        esc_url(
          wp_nonce_url(
            add_query_arg(
              [
                'action' => self::ACTION_KEY,
                'post' => $post->ID
              ],
              admin_url('admin.php')
            ),
            $this->handle,
            self::NONCE_KEY
          )
        )
      );
      $return['duplicate'] = $link;
    }

    return $return;
  }
}
