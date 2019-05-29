<?php

namespace WesleyChang\WPDP\Routes;

class Base
{

  public $namespace;

  public $version;

  public function __construct()
  {

    $this->version = 'v1';
    $this->namespace = "/wcwpdp/$this->version";
  }
}