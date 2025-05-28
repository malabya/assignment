<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helper method to render Person Card and Person Card List elements.
 */
trait PersonCardThemeTrait {
  use ElementWrapThemeTrait;
  use TitleAndLabelsThemeTrait;

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
    $elements = [];
    $element = [
      '#theme' => 'image',
      '#uri' => $image_url,
      '#alt' => $alt,
      '#width' => 100,
    ];

    $inner_elements = [];
    $inner_elements[] = $this->wrapRoundedCornersFull($element);
    $element = $this->wrapTextResponsiveFontSize($name, FontSizeEnum::Sm);
    $element = $this->wrapTextFontWeight($element, FontWeightEnum::Normal);
    $inner_elements[] = $this->wrapTextCenter($element);

    if ($organization) {
      $element = $this->wrapTextResponsiveFontSize($organization, FontSizeEnum::Sm);
      $element = $this->wrapTextCenter($element);
      $inner_elements[] = $this->wrapTextColor($element, TextColorEnum::Gray);
    }

    if ($designation) {
      $inner_elements[] = $this->buildBadgeFromText([$designation], BadgeColorEnum::Green);
    }

    $elements[] = $this->wrapContainerVerticalSpacing($inner_elements, AlignmentEnum::Center);

    $footer_elements_inner = [];
    $footer_elements_inner[] = $this->buildFooterElement($email, 'Email', LinkTypeEnum::Email);
    if ($phone) {
      $footer_elements_inner[] = $this->buildFooterElement($phone, 'Phone', LinkTypeEnum::Phone);
    }

    $footer_elements[] = $this->wrapContainerGrid($footer_elements_inner, GridColEnum::Two, TRUE);

    return $this->buildInnerElementLayoutWithFooter($elements, $footer_elements, TRUE);
  }

  /**
   * Build the footer.
   *
   * @param string $item
   *   The text.
   * @param string $label
   *   The label of the element.
   * @param \Drupal\server_general\ThemeTrait\LinkTypeEnum $type
   *   The link type.
   *
   * @return array
   *   The render array.
   */
  private function buildFooterElement(string $item, string $label, LinkTypeEnum $type) {
    $element = $this->buildLinkFromText($item, $label, $type);
    $element = $this->wrapTextResponsiveFontSize($element, FontSizeEnum::Sm);
    $element = $this->wrapTextFontWeight($element, FontWeightEnum::Medium);
    return $this->wrapTextCenter($element);
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
