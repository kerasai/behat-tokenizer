<?php

namespace Kerasai\Behat\Tokenizer;

/**
 * Trait TokenizerAwareTrait.
 */
trait TokenizerAwareTrait {

  /**
   * The tokenizer.
   *
   * @var \Kerasai\Behat\Tokenizer\Tokenizer
   */
  protected $tokenizer;

  /**
   * Set the tokenizer.
   *
   * @param \Kerasai\Behat\Tokenizer\Tokenizer $tokenizer
   *   The tokenizer.
   *
   * @return $this
   */
  public function setTokenizer(Tokenizer $tokenizer) {
    $this->tokenizer = $tokenizer;
    return $this;
  }

}
