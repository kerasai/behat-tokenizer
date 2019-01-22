The Behat Tokenizer allows you to set and utilize tokens in your Behat test 
suite.

Installing Behat Tokenizer
--------------------------

Install the Behat tokenizer using [Composer](https://getcomposer.org):

```bash
$> composer require --dev kerasai/behat-tokenizer
```

Configuring Behat Tokenizer
---------------------------

Add the `TokenizerExtension` class to the `behat.yml` file and tokens will be 
processed.

```yaml
  extensions:
    Kerasai\Behat\Tokenizer\TokenizerExtension: { }
```


Using Behat Tokenizer in Contexts
---------------------------------

To utilize the Behat Tokenizer in context classes, simple implement `\Kerasai\Behat\Tokenizer\TokenizerAwareInterface`
and the context will have its `::setTokenizer` method called with the tokenizer.

Additionally you may `use \Kerasai\Behat\Tokenizer\TokenizerAwareTrait` to 
easily implement the interface.

From there you may access the tokenizer within the context:

```php
<?php

use \Kerasai\Behat\Tokenizer\TokenizerAwareInterface;
use \Kerasai\Behat\Tokenizer\TokenizerAwareTrait;

class MyContext implements TokenizerAwareInterface {
  
  use TokenizerAwareTrait;
  
  /**
   * @Given something named :arg1
   */
  public function AssertSomethingNamed($arg1) {
    $something = new Something();
    $something->setName($arg1);
    $something->save();
    $this->tokenizer->setToken("[something:$arg1]", $something->id());
  }
  
}
``` 

The token will be processed for all arguments within Behat features:

```plaintext
Feature: View a something
  In order see something
  As an visitor to the website
  I need to view pages with somthing

  Scenario: Something page
    Given something named "great_thing"
    When I am on "something/[something:great_thing]"
    Then I should see the heading "great_thing"
```


Custom Token Replacers
----------------------

In addition to explicit token setting and replacement, custom replacer classes 
may be implemented to perform token replacement.

Replacer classes must implement `\Kerasai\Behat\Tokenizer\Replacer\ReplacerInterface`
and may extend `\Kerasai\Behat\Tokenizer\Replacer\ReplacerBase` for convenience.

See `\Kerasai\Behat\Tokenizer\Replacer\TimestampReplacer`.

In order to utilize the custom replacer classes, they must be exposed to the 
Behat Tokenizer in the `behat.yml` configuration. In the simplest form, just the
class name may be utilized.

```yaml
  extensions:
    Kerasai\Behat\Tokenizer\TokenizerExtension:
      replacers:
        - Kerasai\Behat\Tokenizer\Replacer\TimestampReplacer
        - Custom\Replacer\ReplacerNoConfig
        - Custom\Replacer\ReplacerWithConfig:
            config_option_1: abc
            config_option_2: def
        - special_key:
            class: Custom\Replacer\ReplacerHasSpecialKey
            config_option_1: abc
            config_option_2: def
```
