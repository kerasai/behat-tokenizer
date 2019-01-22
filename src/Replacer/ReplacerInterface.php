<?php

namespace Kerasai\Behat\Tokenizer\Replacer;

/**
 * Interface ReplacerInterface.
 */
interface ReplacerInterface {

  /**
   * Replaces values.
   *
   * @param string $value
   *   The token.
   *
   * @return string
   *   The replaced value.
   */
  public function replace($value);

  /**
   * Resets the replacer.
   */
  public function reset();

}
