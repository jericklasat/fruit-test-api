<?php

declare(strict_types=1);

namespace App\Enum;

enum Response: string
{
  case NO_DATA = 'No data found.';
  case FETCHING_SUCCESS = "Fetching data success.";
  case FETCHING_FAILED = "Fetching data failed.";
  case CREATING_FAILED = "Creating new record failed.";
  case CREATING_SUCCESS = "Creating new record successful.";
}