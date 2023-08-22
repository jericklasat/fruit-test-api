<?php

namespace App\Common;

class AppFormatter
{
  /**
   * @param string $message
   * @param mixed $data
   * @param array<string, string>|null $errors
   * @return array<string, mixed>
   */
  public function formatResponse(string $message, mixed $data, ?array $errors = null): array
  {
    $response = ['message' => $message];
    if ($data != null) {
        $response['data'] = $data;
    }
    if ($errors != null) {
        $response['errors'] = $errors;
    }

    return $response;
  }

  /**
   * @return array<string, mixed>
   */
  public function formatPagination($totalItems, $pageCount, $pageItems): array
  {
      return [
          "itemCount" => $totalItems,
          "pageCount" => (int) $pageCount,
          "items" => $pageItems
      ];
  }
}