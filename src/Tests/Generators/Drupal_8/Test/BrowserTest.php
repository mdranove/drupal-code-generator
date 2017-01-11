<?php

namespace DrupalCodeGenerator\Tests\Drupal_8\Test;

use DrupalCodeGenerator\Tests\GeneratorTestCase;

/**
 * Test for d8:test:browser command.
 */
class BrowserTest extends GeneratorTestCase {

  protected $class = 'Drupal_8\Test\Browser';

  protected $answers = [
    'Foo',
    'foo',
    'Example',
  ];

  protected $fixtures = [
    'tests/src/Functional/ExampleTest.php' => __DIR__ . '/_browser.php',
  ];

}
