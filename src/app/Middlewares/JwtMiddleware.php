<?php

namespace App\Middlewares;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Tuupola\Middleware\JwtAuthentication;

class JwtMiddleware
{
  private $container;

  public function __construct($container)
  {
    $this->container = $container;
  }

  public function __invoke(Request $request, Response $response, $next)
  {
    $jwtMiddleware = new JwtAuthentication([
      "path" => "/api",
      "secure" => false,
      "secret" => $this->container['settings']['jwt']['key'],
      "error" => function ($res, $args) {
        return $res->withJSON([
          'success' => false,
          'message' => $args['message'],
          'status' => 401
        ]);
      }
    ]);

    return $jwtMiddleware($request, $response, $next);
  }
}
