<?php

namespace WesleyChang\WPDP;

class Init
{
  public static function get_services()
  {
    return [
      Setup\Actions::class,
      Setup\Enqueue::class,
      Pages\AdminDashboard::class,
      Controllers\DuplicatePost::class,
      Routes\DuplicatePostRoutes::class,
    ];
  }

  public static function register_services()
  {
    foreach (self::get_services() as $class) {
      $service = self::instantiate($class);

      if (method_exists($service, "register")) {
        $service->register();
      }
    }
  }

  private static function instantiate($class)
  {
    $service = new $class();

    return $service;
  }
}