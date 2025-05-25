<?php

declare(strict_types=1);

namespace Drupal\server_general\ThemeTrait;

use Drupal\Core\Render\Markup;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\intl_date\IntlDate;
use Drupal\og\Og;
use Drupal\og\OgMembershipInterface;
use Drupal\server_general\EntityDateTrait;

/**
 * Helper method for building the Node group element.
 */
trait ElementNodeGroupThemeTrait {

  use ElementWrapThemeTrait;
  use EntityDateTrait;
  use InnerElementLayoutThemeTrait;
  use LineSeparatorThemeTrait;
  use ElementLayoutThemeTrait;
  use TitleAndLabelsThemeTrait;

  /**
   * Build the Group news element.
   *
   * @param string $title
   *   The node title.
   * @param int $timestamp
   *   The timestamp.
   * @param array $image
   *   The responsive image render array.
   * @param array $body
   *   The body render array.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user object.
   * @param int $group_id
   *   The current Group entity ID.
   *
   * @return array
   *   The render array.
   *
   * @throws \IntlException
   */
  protected function buildElementNodeGroup(string $title, int $timestamp, array $image, array $body, AccountProxyInterface $current_user, int $group_id): array {
    $elements = [];

    // Header.
    $element = $this->buildHeader(
      $title,
      $timestamp
    );
    $elements[] = $this->wrapContainerWide($element);

    // Main content and sidebar.
    $element = $this->buildMainAndSidebar(
      $title,
      $image,
      $this->wrapProseText($body),
      $current_user,
      $group_id
    );
    $elements[] = $this->wrapContainerWide($element);

    $elements = $this->wrapContainerVerticalSpacingBig($elements);
    return $this->wrapContainerBottomPadding($elements);
  }

  /**
   * Build the header.
   *
   * @param string $title
   *   The node title.
   * @param int $timestamp
   *   The timestamp.
   *
   * @return array
   *   Render array.
   *
   * @throws \IntlException
   */
  private function buildHeader(string $title, int $timestamp): array {
    $elements = [];

    $elements[] = $this->buildPageTitle($title);

    // Date.
    $element = IntlDate::formatPattern($timestamp, 'long');

    // Make text bigger.
    $elements[] = $this->wrapTextResponsiveFontSize($element, FontSizeEnum::LG);

    $elements = $this->wrapContainerVerticalSpacing($elements);

    return $this->wrapContainerMaxWidth($elements, WidthEnum::ThreeXl);
  }

  /**
   * Build the Main content and the sidebar.
   *
   * @param string $title
   *   The node title.
   * @param array $image
   *   The responsive image render array.
   * @param array $body
   *   The body render array.
   * @param \Drupal\Core\Session\AccountProxyInterface $current_user
   *   The current user object.
   * @param int $group_id
   *   The current Group entity ID.
   *
   * @return array
   *   Render array
   */
  private function buildMainAndSidebar(string $title, array $image, array $body, AccountProxyInterface $current_user, int $group_id): array {
    $main_elements = [];
    $sidebar_elements = [];

    $main_elements = [$image, $body];

    $group = \Drupal::routeMatch()->getParameter('node');
    $account = $current_user->getAccount();

    // Check if the user is anonymous & return early.
    if ($account->isAnonymous()) {
      $sidebar_elements[] = [
        '#markup' => Markup::create(sprintf(
          'Please <a href="/user/login">Log in</a> to your account subscribe to the group: %s',
          $title
        )),
      ];

      return $this->buildElementLayoutMainAndSidebar(
        $this->wrapContainerVerticalSpacingBig($main_elements),
        $this->buildInnerElementLayout($this->wrapRoundedCornersBig($sidebar_elements)),
      );
    }

    // Check if the user already a member of the group.
    if (Og::isMember($group, $account)) {
      $sidebar_elements[] = [
        '#markup' => Markup::create(sprintf(
          'Hi %s, you are already subscribed to the group: %s',
          $current_user->getAccount()->getDisplayName(),
          $title
        )),
      ];
    }
    elseif (!empty(\Drupal::entityTypeManager()
      ->getStorage('og_membership')
      ->loadByProperties([
        'uid' => $account->id(),
        'entity_type' => $group->getEntityTypeId(),
        'entity_id' => $group->id(),
        'state' => OgMembershipInterface::STATE_PENDING,
      ]))) {
      $sidebar_elements[] = [
        '#markup' => Markup::create(sprintf(
          'Hi %s, you already have a pending membership for the the group: %s',
          $current_user->getAccount()->getDisplayName(),
          $title
        )),
      ];
    }
    else {
      $sidebar_elements[] = [
        '#markup' => Markup::create(sprintf(
          'Hi %s, <a href="/group/node/%d/subscribe">Click Here</a> if you would like to subscribe to this group: %s',
          $current_user->getAccount()->getDisplayName(),
          $group_id,
          $title
        )),
      ];
    }

    return $this->buildElementLayoutMainAndSidebar(
      $this->wrapContainerVerticalSpacingBig($main_elements),
      $this->buildInnerElementLayout($this->wrapRoundedCornersBig($sidebar_elements)),
    );
  }

}
