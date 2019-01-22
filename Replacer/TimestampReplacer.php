<?php

namespace Kerasai\Behat\Tokenizer\Replacer;

/**
 * Tokenizer replacer for the 'timestamp:<relative time>[:<format>]' token.
 */
class TimestampReplacer extends ReplacerBase {

  /**
   * {@inheritdoc}
   */
  public function replace($value) {
    // Match the token.
    if (!preg_match_all('/\[timestamp:([^\]]+)\]/', $value, $matches, PREG_SET_ORDER)) {
      return $value;
    }

    // Process matches.
    foreach ($matches as $match) {
      // Pull out a timestamp and a format.
      $parts = explode(':', $match[1]);
      $timestamp = array_shift($parts);
      $timestamp = strtotime($timestamp);
      $format = array_shift($parts);

      // Ensure timestamp converted to time.
      if ($timestamp === FALSE) {
        throw new \InvalidArgumentException(sprintf('Unable to create timestamp from token value "%s"', $match[1]));
      }

      // If there was a format specified, apply it.
      if ($format) {
        $timestamp = date($format, $timestamp);
      }

      // Replace the tokens.
      $value = str_replace($match[0], $timestamp, $value);
    }

    return $value;
  }

}
