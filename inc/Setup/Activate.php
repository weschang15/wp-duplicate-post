<?php

namespace WesleyChang\WPDP\Setup;

class Activate
{
  public static function activate()
  {
    flush_rewrite_rules();
  }
}