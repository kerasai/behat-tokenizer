<?php

namespace Kerasai\Behat\Tokenizer;

use Behat\Behat\Context\ServiceContainer\ContextExtension;
use Behat\Behat\Transformation\ServiceContainer\TransformationExtension;
use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Kerasai\Behat\Tokenizer\Context\Initializer\TokenizerAwareInitializer;
use Kerasai\Behat\Tokenizer\EventSubscriber\BeforeScenarioEventListener;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class TokenizerExtension.
 */
class TokenizerExtension implements ExtensionInterface {

  /**
   * {@inheritdoc}
   */
  public function process(ContainerBuilder $container) {
    // No op.
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'kerasai_tokenizer';
  }

  /**
   * {@inheritdoc}
   */
  public function initialize(ExtensionManager $extensionManager) {
    // TODO: Implement initialize() method.
  }

  /**
   * {@inheritdoc}
   */
  public function configure(ArrayNodeDefinition $builder) {
    $builder->children()

      // Replacers.
      ->arrayNode('replacers')
      ->useAttributeAsKey('key')
      ->info('Replacer classes.')
      ->prototype('variable')
      ->end()
      ->end()

      // End children.
      ->end();
  }

  /**
   * {@inheritdoc}
   */
  public function load(ContainerBuilder $container, array $config) {
    $container->setParameter('kerasai.tokenizer', $config);

    $service = new Definition(Tokenizer::class);
    $service->addArgument('%kerasai.tokenizer%');
    $service->addTag(TransformationExtension::ARGUMENT_TRANSFORMER_TAG);
    $container->setDefinition('kerasai.tokenizer', $service);

    $service = new Definition(TokenizerAwareInitializer::class);
    $service->addArgument(new Reference('kerasai.tokenizer'));
    $service->addTag(ContextExtension::INITIALIZER_TAG);
    $container->setDefinition('kerasai.tokenizer.context.initializer', $service);

    $service = new Definition(BeforeScenarioEventListener::class);
    $service->addArgument(new Reference('kerasai.tokenizer'));
    $service->addTag('event_dispatcher.subscriber');
    $container->setDefinition('kerasai.tokenizer.before_scenario_listener', $service);
  }

}
