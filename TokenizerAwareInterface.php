<?php

namespace Kerasai\Behat\Tokenizer;

/**
 * Interface TokenizerAwareInterface.
 */
interface TokenizerAwareInterface {

  /**
   * Set the tokenizer.
   *
   * @param \Kerasai\Behat\Tokenizer\Tokenizer $tokenizer
   *   The tokenizer.
   *
   * @return $this
   */
  public function setTokenizer(Tokenizer $tokenizer);

}
