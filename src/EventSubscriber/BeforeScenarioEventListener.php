<?php

namespace Kerasai\Behat\Tokenizer\EventSubscriber;

use Behat\Behat\EventDispatcher\Event\ScenarioTested;
use Kerasai\Behat\Tokenizer\Tokenizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class BeforeStepEventListener.
 */
class BeforeScenarioEventListener implements EventSubscriberInterface {

  /**
   * The tokenizer.
   *
   * @var \Kerasai\Behat\Tokenizer\Tokenizer
   */
  protected $tokenizer;

  /**
   * TokenizerAwareInitializer constructor.
   *
   * @param \Kerasai\Behat\Tokenizer\Tokenizer $tokenizer
   *   The tokenizer.
   */
  public function __construct(Tokenizer $tokenizer) {
    $this->tokenizer = $tokenizer;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      ScenarioTested::BEFORE => [
        ['resetTokens', 0],
      ],
    ];
  }

  /**
   * Resets the tokenizer tokens.
   */
  public function resetTokens() {
    $this->tokenizer->reset();
  }

}
