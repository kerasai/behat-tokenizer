<?php

namespace Kerasai\Behat\Tokenizer\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Kerasai\Behat\Tokenizer\Tokenizer;
use Kerasai\Behat\Tokenizer\TokenizerAwareInterface;

/**
 * Class TokenizerAwareInitializer.
 */
class TokenizerAwareInitializer implements ContextInitializer {

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
  public function initializeContext(Context $context) {
    if (!$context instanceof TokenizerAwareInterface) {
      return;
    }
    $context->setTokenizer($this->tokenizer);
  }

}
