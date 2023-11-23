<?php

namespace App\Helpers;

use App\Enums\HttpStatus;
use Slim\Http\Response;

class ResponseHelper
{

  private $response;

  public function __construct(Response $response)
  {
    $this->response = $response;
  }

  public function createErrorResponse($message = 'failed', $status = HttpStatus::SERVER->value): Response
  {
    $responseData = [
      'message' => $message,
      'success' => false
    ];


    return $this->response->withJson($responseData)->withStatus($status);
  }

  public function createSuccessResponse($data, $message = 'success', $status = HttpStatus::OK->value): Response
  {
    $responseData = [
      'message' => $message,
      'success' => true
    ];

    // Check if $data is an array and add it to the response if present
    if (is_array($data)) {
      $responseData['data'] = $data;
    }

    return $this->response->withJson($responseData)->withStatus($status);
  }
}
