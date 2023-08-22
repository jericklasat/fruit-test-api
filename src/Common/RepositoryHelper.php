<?php

declare(strict_types=1);

namespace App\Common;

use Doctrine\DBAL\Connection;

class RepositoryHelper
{
  public function getCustomQueryPaginatedTotalItems(Connection $conn, string $sql, ?array $bindValue = []): int
  {
      $stmt = $conn->prepare($sql);

      foreach ($bindValue as $key => $value) {
          $stmt->bindValue($key, $value[0], $value[1]);
      }

      return $stmt->executeQuery()->rowCount();
  }
}