<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Enum for link type options used in theme wrappers.
 */
enum LinkTypeEnum: string
{
  case Email = 'email';
  case Phone = 'phone';
}
