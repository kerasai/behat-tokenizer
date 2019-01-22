<?php

namespace Kerasai\Behat\Tokenizer\Replacer;

/**
 * Class ReplacerBase.
 */
abstract class ReplacerBase implements ReplacerInterface {

  protected $options;

  /**
   * ReplacerBase constructor.
   *
   * @param array $options
   *   Various options for the replacer.
   */
  public function __construct(array $options = []) {
    $this->options = $options;
  }

  /**
   * {@inheritdoc}
   */
  public function reset() {
    // No op.
  }

}
