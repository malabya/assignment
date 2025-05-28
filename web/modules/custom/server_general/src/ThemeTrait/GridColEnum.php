<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Enum for width options used in theme wrappers.
 */
enum GridColEnum: string {
  case Two = '2';
  case Three = '3';
}
