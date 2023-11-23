<?php

namespace App\Interfaces;

use Slim\Http\Request;
use Slim\Http\Response;

interface StudentInterface {
  public function findAll(Request $request, Response $response): Response;
  public function findById(Request $request, Response $response, $args): Response;
  public function save(Request $request, Response $response): Response;
  public function delete(Request $request, Response $response): Response;
  public function update(Request $request, Response $response): Response;
}