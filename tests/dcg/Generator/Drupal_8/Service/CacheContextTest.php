<?php

namespace DrupalCodeGenerator\Tests\Generator\Drupal_8\Service;

use DrupalCodeGenerator\Tests\Generator\GeneratorBaseTest;

/**
 * Test for d8:service:cache-context command.
 */
class CacheContextTest extends GeneratorBaseTest {

  protected $class = 'Drupal_8\Service\CacheContext';

  /**
   * Test callback.
   */
  public function testGenerator() {

    $interaction = [
      'Module name [%default_name%]:' => 'Foo',
      'Module machine name [foo]:' => 'foo',
      'Context ID [example]:' => 'example',
      'Class [ExampleCacheContext]:' => 'ExampleCacheContext',
      "Base class:\n  [0] -\n  [1] RequestStackCacheContextBase\n  [2] UserCacheContextBase" => 1,
      'Make the context calculated? [No]:' => 'No',
    ];

    $fixtures = [
      'foo.services.yml' => __DIR__ . '/_cache_context.services.yml',
      'src/Cache/Context/ExampleCacheContext.php' => __DIR__ . '/_cache_context.php',
    ];

    parent::doTest($interaction, $fixtures);

    parent::tearDown();

    $interaction = [
      'Module name [%default_name%]:' => 'Bar',
      'Module machine name [bar]:' => 'bar',
      'Context ID [example]:' => 'example',
      'Class [ExampleCacheContext]:' => 'ExampleCacheContext',
      "Base class:\n  [0] -\n  [1] RequestStackCacheContextBase\n  [2] UserCacheContextBase" => 1,
      'Make the context calculated? [No]:' => 'Yes',
    ];

    $fixtures = [
      'bar.services.yml' => __DIR__ . '/_cache_context_calculated.services.yml',
      'src/Cache/Context/ExampleCacheContext.php' => __DIR__ . '/_cache_context_calculated.php',
    ];

    parent::doTest($interaction, $fixtures);
  }

}
