<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

/**
 * Helper method for building Title and labels of a content.
 */
trait TitleAndLabelsThemeTrait {

  use ElementWrapThemeTrait;

  /**
   * Build the page title element.
   *
   * @param string $title
   *   The title.
   *
   * @return array
   *   The render array.
   */
  protected function buildPageTitle(string $title): array {
    return [
      '#theme' => 'server_theme_page_title',
      '#title' => $title,
    ];
  }

  /**
   * Build the labels from text.
   *
   * @param array $labels
   *   The Labels to show.
   *
   * @return array
   *   Render array.
   */
  protected function buildLabelsFromText(array $labels): array {
    // Type labels.
    $items = [];

    foreach ($labels as $label) {
      $items[] = [
        '#theme' => 'server_theme_label',
        '#label' => $label,
      ];
    }

    return [
      '#theme' => 'server_theme_labels',
      '#items' => $items,
    ];
  }

  /**
   * Build the paragraph title.
   *
   * @param string $title
   *   The title.
   *
   * @return array
   *   Render array.
   */
  protected function buildParagraphTitle(string $title): array {
    return $this->wrapHtmlTag($title, 'h2');
  }

  /**
   * Wrap an element with text color.
   *
   * @param array $elements
   *   The array of text.
   * @param \Drupal\server_general\ThemeTrait\BadgeColorEnum $color
   *   The badge color.
   *
   * @return array
   *   Render array.
   */
  protected function buildBadgeFromText(array $elements, BadgeColorEnum $color): array {
    $items = [];

    if (empty($elements)) {
      return [];
    }

    foreach ($elements as $element) {
      $items[] = [
        '#theme' => 'server_theme_badge',
        '#label' => $element,
        '#color' => $color->value,
      ];
    }

    return [
      '#theme' => 'server_theme_labels',
      '#items' => $items,
    ];
  }

  /**
   * Wrap an element with link for CTA buttons like email, phone, etc.
   *
   * @param string $element
   *   The link string.
   * @param string $link_label
   *   The link label.
   * @param \Drupal\server_general\ThemeTrait\LinkTypeEnum $type
   *   The link type.
   *
   * @return array
   *   Render array.
   */
  protected function buildLinkFromText(string $element, string $link_label, LinkTypeEnum $type): array {
    return [
      '#theme' => 'server_theme_link_cta',
      '#link' => $element,
      '#label' => $link_label,
      '#type' => $type->value,
    ];
  }

}
