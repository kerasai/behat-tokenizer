<?php

namespace Kerasai\Behat\Tokenizer;

use Behat\Behat\Definition\Call\DefinitionCall;
use Behat\Behat\Transformation\Transformer\ArgumentTransformer;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Kerasai\Behat\Tokenizer\Replacer\ReplacerInterface;

/**
 * Class Tokenizer.
 */
class Tokenizer implements ArgumentTransformer {

  /**
   * Token replacement pairs.
   *
   * @var array
   */
  protected $tokens = [];

  /**
   * Replacers.
   *
   * @var \Kerasai\Behat\Tokenizer\Replacer\ReplacerInterface[]
   */
  protected $replacers = [];

  /**
   * Tokenizer constructor.
   *
   * @param array $config
   *   Tokenizer configuration.
   */
  public function __construct(array $config) {
    if (!empty($config['replacers'])) {
      $this->setReplacers($config['replacers']);
    }
  }

  /**
   * Sets replacers.
   *
   * @param array $replacersConfig
   *   Replacer configurations.
   */
  protected function setReplacers(array $replacersConfig) {
    foreach ($replacersConfig as $name => $config) {
      if (!is_array($config)) {
        $config = ['class' => $config];
      }
      $class = !empty($config['class']) ? $config['class'] : $name;
      $replacer = new $class($config);
      if (!$replacer instanceof ReplacerInterface) {
        throw new \InvalidArgumentException(sprintf('Replacer class "%s" must implement ReplacerInterface.', $class));
      }
      $this->replacers[$name] = $replacer;
    }
  }

  /**
   * Gets a replacer.
   *
   * @param string $name
   *   The name of the replacer to get.
   *
   * @return \Kerasai\Behat\Tokenizer\Replacer\ReplacerInterface
   *   The replacer.
   */
  public function getReplacer($name) {
    if (!array_key_exists($name, $this->replacers)) {
      throw new \InvalidArgumentException(sprintf('No replacer "%s" available.', $name));
    }

    return $this->replacers[$name];
  }

  /**
   * {@inheritdoc}
   */
  public function supportsDefinitionAndArgument(DefinitionCall $definitionCall, $argumentIndex, $argumentValue) {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  public function transformArgument(DefinitionCall $definitionCall, $argumentIndex, $argumentValue) {
    if ($argumentValue instanceof TableNode) {
      $argumentValue = $this->tokenReplaceTableNode($argumentValue);
    }
    elseif ($argumentValue instanceof PyStringNode) {
      $argumentValue = $this->tokenReplacePyStringNode($argumentValue);
    }
    else {
      $argumentValue = $this->tokenReplace($argumentValue);
    }

    return $argumentValue;
  }

  /**
   * Replaces tokens in a string.
   *
   * @param string $value
   *   The initial value.
   *
   * @return string
   *   The value with tokens replaced.
   */
  protected function tokenReplace($value) {
    $value = strtr($value, $this->tokens);
    foreach ($this->replacers as $replacer) {
      $value = $replacer->replace($value);
    }
    return $value;
  }

  /**
   * Replaces tokens found in TableNode data.
   *
   * @param \Behat\Gherkin\Node\TableNode $value
   *   The initial value.
   *
   * @return \Behat\Gherkin\Node\TableNode
   *   The value with tokens replaced.
   */
  protected function tokenReplaceTableNode(TableNode $value) {
    $tableData = [];
    $rows = array_combine($value->getLines(), $value->getRows());
    foreach ($rows as $key => $row) {
      $tableData[$key] = array_map(function ($item) {
        return $this->tokenReplace($item);
      }, $row);
    }
    return new TableNode($tableData);
  }

  /**
   * Replaces tokens found in TableNode data.
   *
   * @param \Behat\Gherkin\Node\PyStringNode $value
   *   The initial value.
   *
   * @return \Behat\Gherkin\Node\PyStringNode
   *   The value with tokens replaced.
   */
  protected function tokenReplacePyStringNode(PyStringNode $value) {
    $strings = array_map(function ($item) {
      return $this->tokenReplace($item);
    }, $value->getStrings());
    return new PyStringNode($strings, $value->getLine());
  }

  /**
   * Sets a token into storage.
   *
   * @param string $token
   *   The token.
   * @param string $replacement
   *   The replacement value for the token.
   *
   * @return $this
   */
  public function setToken($token, $replacement) {
    $this->tokens[$token] = $replacement;
    return $this;
  }

  /**
   * Resets the stored tokens.
   */
  public function reset() {
    $this->tokens = [];
    foreach ($this->replacers as $replacer) {
      $replacer->reset();
    }
  }

}
