<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Enum for badge options used in theme wrappers.
 */
enum BadgeColorEnum: string {
  case Gray = 'gray';
  case Red = 'red';
  case Green = 'green';
}
