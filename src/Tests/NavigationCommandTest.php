<?php

namespace DrupalCodeGenerator\Tests;

use DrupalCodeGenerator\Commands\Navigation;
use DrupalCodeGenerator\GeneratorDiscovery;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Filesystem\Filesystem;

/**
 * A test for navigation command.
 */
class NavigationTest extends TestCase {

  protected $destination;

  /**
   * Test callback.
   */
  public function testExecute() {

    $this->destination = DCG_SANDBOX . '/example';

    // Create navigation command.
    $commands_directories[] = DCG_ROOT . '/src/Commands';
    $twig_directories[] = DCG_ROOT . '/src/Templates';
    $discovery = new GeneratorDiscovery(new Filesystem());
    $generators = $discovery->getGenerators($commands_directories, $twig_directories);

    $application = new Application();
    $application->addCommands($generators);

    $navigation = new Navigation($generators);
    $application->add($navigation);

    $helper = $navigation->getHelper('question');

    $answers = [
      1, 1, 0, 2, 0, 0,
      2, 1, 0, 2, 0, 3, 0, 4, 0, 5, 0, 6, 0, 7, 0, 0,
      3, 0,
      9, 0, 0,
    ];
    $helper->setInputStream($this->getInputStream(implode("\n", $answers)));

    $commandTester = new CommandTester($navigation);
    $commandTester->execute(['command' => $navigation->getName(), '--directory' => $this->destination]);

    $output = trim($commandTester->getDisplay());
    $expected_output = trim(file_get_contents(__DIR__ . '/_navigation-fixture.txt'));
    $this->assertEquals($output, $expected_output);
  }

  /**
   * Returns input stream.
   */
  protected function getInputStream($input) {
    $stream = fopen('php://memory', 'r+', FALSE);
    fwrite($stream, $input);
    rewind($stream);
    return $stream;
  }

  /**
   * {@inheritdoc}
   */
  public function tearDown() {
    (new Filesystem())->remove($this->destination);
  }

}
