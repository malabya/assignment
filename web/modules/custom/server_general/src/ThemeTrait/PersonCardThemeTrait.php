<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helper method to render Person and PersonList elements.
 */
trait PersonCardThemeTrait
{
  protected function buildElementPersonCard(string $image_url, string $alt, string $name, string $email, string $phone, ?string $organization = NULL, ?string $designation = NULL)
  {
    return [
      '#theme' => 'server_theme_person_card',
      '#image' => $image_url,
      '#alt' => $alt,
      '#name' => $name,
      '#organization' => $organization,
      '#designation' => $designation,
      '#email' => $email,
      '#phone' => $phone
    ];
  }
}
