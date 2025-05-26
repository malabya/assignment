<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helper method to render Person Card and Person Card List elements.
 */
trait PersonCardThemeTrait {
  use ElementWrapThemeTrait;

  /**
   * Builds a render array for displaying a person card.
   *
   * @param string $image_url
   *   The URL of the person's image.
   * @param string $alt
   *   The alt text for the image, used for accessibility.
   * @param string $name
   *   The name of the person.
   * @param string $email
   *   The email address of the person.
   * @param string|null $organization
   *   (Optional) The organization the person belongs to.
   * @param string|null $phone
   *   (Optional) The phone number of the person.
   * @param string|null $designation
   *   (Optional) The designation or title of the person.
   *
   * @return array
   *   A render array for the person card component.
   */
  protected function buildElementPersonCard(string $image_url, string $alt, string $name, string $email, ?string $organization = NULL, ?string $phone = NULL, ?string $designation = NULL) {
    return [
      '#theme' => 'server_theme_person_card',
      '#image' => $image_url,
      '#alt' => $alt,
      '#name' => $name,
      '#organization' => $organization,
      '#designation' => $designation,
      '#email' => $email,
      '#phone' => $phone,
    ];
  }

  /**
   * Builds a list of person cards wrapped in a wide container.
   *
   * @param array $items
   *   An array of associative arrays, each containing data needed to build
   *   a person card (e.g., name, email, image URL, etc.).
   *
   * @return array
   *   A render array representing the list of person cards in a wide container.
   */
  protected function buildElementPersonCardList($items) {
    return $this->wrapContainerWide($this->buildCards($items));
  }

}
