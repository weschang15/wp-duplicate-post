<?php

namespace WesleyChang\WPDP\Routes;

class DuplicatePostRoutes extends Base
{
  const PLUGIN_OPTIONS = 'wc_wpdp_options';
  const PLUGIN_FEATURE_KEY = 'duplicate_post';
  const PLUGIN_FEATURE_DEFAULTS = 'wc_wpdp_duplicate_post_defaults';

  private $plugin_options;

  private $feature_defaults;

  public function register()
  {
    $this->plugin_options = get_option(self::PLUGIN_OPTIONS);
    $this->feature_defaults = get_option(self::PLUGIN_FEATURE_DEFAULTS);

    add_action('rest_api_init', [$this, 'routes']);
  }

  public function routes()
  {
    register_rest_route($this->namespace, '/duplicatePost/settings/', [
      'methods' => 'POST',
      'callback' => [$this, 'settings_route_handler'],
      'permissions_callback' => function () {
        return current_user_can('administrator');
      },
      'args' => $this->get_settings_endpoint_args()
    ]);
  }

  public function get_settings_endpoint_args()
  {
    $args = [];

    $post_types = isset($this->feature_defaults)
      ? $this->feature_defaults['post_types']
      : null;

    // Here we add our PHP representation of JSON Schema.
    $args['status'] = [
      'description' => esc_html('Status of Duplicate Post feature'),
      'type' => 'string',
      'enum' => ["1"],
      'sanitize_callback' => ['WesleyChang\WPDP\Helpers\Sanitizers', 'sanitize_int'],
      'validate_callback' => [$this, 'validate_endpoint_arg']
    ];

    $args['post_types'] = [
      'description' => esc_html(
        'Post Types which allow the Duplicate Post feature'
      ),
      'type' => 'array',
      'enum' => $post_types,
      'sanitize_callback' => ['WesleyChang\WPDP\Helpers\Sanitizers', 'sanitize_array'],
      'validate_callback' => [$this, 'validate_endpoint_arg']
    ];

    return $args;
  }

  public function validate_endpoint_arg($param, $req, $key)
  {
    $attributes = $req->get_attributes();
    if (isset($attributes['args'][$key])) {
      $argument = $attributes['args'][$key];
      // Check to make sure our argument is a string.
      if ('string' === $argument['type']) {
        if (!is_string($param)) {
          return new \WP_Error(
            "InvalidParameter",
            sprintf(esc_html('%s is not of type %s'), $key, 'string')
          );
        } else {
          if (isset($argument['enum'])) {
            $enum = $argument['enum'];
            $valid = in_array($param, $enum);
            if (!$valid) {
              return new \WP_Error(
                "InvalidParameter",
                sprintf(
                  esc_html('%s is not one of %s'),
                  $key,
                  implode(' ', $enum)
                )
              );
            }
          }
        }
      }

      if ('array' === $argument['type']) {
        if (!is_array($param)) {
          return new \WP_Error(
            "InvalidParameter",
            sprintf(esc_html('%s is not of type %s'), $key, 'array')
          );
        } else {
          if (isset($argument['enum'])) {
            $enum = $argument['enum'];
            $invalid = array_filter($param, function ($post_type) use ($enum) {
              return !in_array($post_type, $enum);
            });

            if (!empty($invalid)) {
              return new \WP_Error(
                "InvalidParameter",
                esc_html(
                  sprintf('Invalid value(s): %s'),
                  implode(' ', $invalid)
                )
              );
            }
          }
        }
      }
    } else {
      // This code won't execute because we have specified this argument as required.
      // If we reused this validation callback and did not have required args then this would fire.
      return new \WP_Error(
        'rest_invalid_param',
        sprintf(esc_html('%s was not registered as a request argument.'), $key),
        ['status' => 400]
      );
    }

    return true;
  }

  public function settings_route_handler(\WP_REST_Request $req)
  {
    $body = $req->get_body_params();
    $activate = isset($body['status']) ? (int)$body['status'] : 0;
    $post_types = isset($body['post_types']) ? $body['post_types'] : [];

    $settings = [
      'status' => $activate,
      'post_types' => $post_types
    ];

    $curr_opts = get_option(self::PLUGIN_OPTIONS);
    $new_opts = [];

    if (isset($curr_opts)) {
      $new_opts[self::PLUGIN_FEATURE_KEY] = isset($curr_opts[self::PLUGIN_FEATURE_KEY])
        ? array_merge($curr_opts[self::PLUGIN_FEATURE_KEY], $settings)
        : $settings;

      update_option(self::PLUGIN_OPTIONS, array_merge($curr_opts, $new_opts));
    }

    return $new_opts;
  }
}