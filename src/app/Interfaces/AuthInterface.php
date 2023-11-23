<?php

namespace App\Interfaces;

use Slim\Http\Request;
use Slim\Http\Response;

interface AuthInterface
{
  public function login(Request $request, Response $response): Response;
  public function register(Request $request, Response $response): Response;
}
