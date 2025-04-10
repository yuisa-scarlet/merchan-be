<?php

namespace App\Helpers;

class ApiResponseFormatter
{
  protected static $response = [
    'meta' => [
      'code' => null,
      'status' => 1,
      'message' => null,
    ],
    'data' => null,
    'errors' => null,
  ];

  public static function success($data = null, $message = null, $code = 200)
  {
    self::$response['meta']['code'] = $code;
    self::$response['meta']['message'] = $message ?? 'Success';
    self::$response['data'] = $data;
    unset(self::$response['errors']);

    return response()->json(self::$response, self::$response['meta']['code']);
  }

  public static function error($data = null, $status = 'error', $code = 400, $message = null, $errors = null)
  {
    self::$response['meta']['status'] = $status;
    self::$response['meta']['code'] = $code;
    self::$response['meta']['message'] = $message ?? 'Error';
    self::$response['data'] = $data;
    self::$response['errors'] = $errors;

    return response()->json(self::$response, self::$response['meta']['code']);
  }
}