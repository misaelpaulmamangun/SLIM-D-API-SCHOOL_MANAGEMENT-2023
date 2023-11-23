<?php

namespace App\Services;

use App\Helpers\AuthHelper;
use App\Helpers\ResponseHelper;
use Psr\Container\ContainerInterface;
use Slim\Container;

class UtilityService
{
  protected $c;

  public function __construct(Container $c)
  {
    $this->c = $c;
  }

  public function configure()
  {
    // Response Helper
    $this->c['ResponseHelper'] = function (ContainerInterface $container) {
      return new ResponseHelper($container->get('response'));
    };

    // Authentication Helper
    $this->c['AuthHelper'] = function (ContainerInterface $container) {
      return new AuthHelper();
    };
  }
}
