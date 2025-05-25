<?php

namespace Drupal\Tests\server_general\ExistingSite;

use Drupal\og\Entity\OgMembership;
use Drupal\og\OgMembershipInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * A model test case using traits from Drupal Test Traits.
 */
class ServerGeneralNodeGroupTest extends ServerGeneralTestBase {
  /**
   * The node entity used in the test.
   *
   * @var \Drupal\node\NodeInterface
   */
  protected $node;

  /**
   * The user entity used in the test.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $user;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->user = $this->createUser([
      'edit own group content',
    ], 'JohnDoe');

    $this->node = $this->createNode([
      'title' => 'Llama',
      'type' => 'group',
      'uid' => $this->user->id(),
      'field_body' => 'This is the text of the body field.',
      'field_featured_image' => ['target_id' => 1],
      'status' => 1,
    ]);
  }

  /**
   * Test permissions and content access for author.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   * @throws \Drupal\Core\Entity\EntityMalformedException
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroup() {
    $this->assertEquals($this->user->id(), $this->node->getOwnerId());

    // We can browse pages.
    $this->drupalGet($this->node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);

    // We can login and browse admin pages.
    $this->drupalLogin($this->user);
    $this->drupalGet($this->node->toUrl('edit-form'));
  }

  /**
   * Test Group access for Owners and Existing Members.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroupAccessForOwnerAndExistingMember() {
    $this->drupalLogin($this->user);
    $this->drupalGet($this->node->toUrl());
    $this->assertSession()->pageTextContains('Hi JohnDoe, you are already subscribed to the group: Llama');
    $this->drupalLogout();
  }

  /**
   * Test Group access for Anonymous users.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroupAccessForAnonymous() {
    $this->drupalGet($this->node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->linkExists('Log in');
    $this->assertSession()->pageTextContains('Please Log in to your account subscribe to the group: Llama');
  }

  /**
   * Test Group access for Authenticated users.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroupAccessForAuthenticated() {
    $user = $this->createUser([], 'FooBar');
    $this->drupalLogin($user);
    $this->drupalGet($this->node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->linkExists('Click Here');
    $link = $this->getSession()->getPage()->findLink('Click Here');
    $this->assertNotNull($link);
    $this->assertEquals('/group/node/' . $this->node->id() . '/subscribe', $link->getAttribute('href'));
    $this->assertSession()->pageTextContains('Hi FooBar, Click Here if you would like to subscribe to this group: Llama');
    $this->drupalLogout();
  }

  /**
   * Test pending group approval.
   *
   * @throws \Behat\Mink\Exception\ExpectationException
   */
  public function testGroupAccessForPendingApproval() {
    $user = $this->createUser([], 'FooBar');

    OgMembership::create([
      'type' => 'og_membership',
      'entity_type' => 'node',
      'entity_id' => $this->node->id(),
      'uid' => $user->id(),
      'state' => OgMembershipInterface::STATE_PENDING,
    ])->save();

    $this->drupalLogin($user);
    $this->drupalGet($this->node->toUrl());
    $this->assertSession()->statusCodeEquals(Response::HTTP_OK);
    $this->assertSession()->pageTextContains('Hi FooBar, you already have a pending membership for the the group: Llama');
    $this->drupalLogout();
  }

}
