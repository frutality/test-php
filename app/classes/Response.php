<?php

class Response
{
  /**
   * @param $status
   * @param $payload
   * @param string $message
   * @return string
   */
  public static function send($status, $payload, $message = '')
  {
    $response = [
      'status' => $status,
      'payload' => $payload,
      'message' => $message
    ];
    
    header('Content-Type: application/json;charset=utf-8');
    return json_encode($response);
  }
}