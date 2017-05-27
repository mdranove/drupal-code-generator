<?php

namespace DrupalCodeGenerator\Commands;

use DrupalCodeGenerator\Utils;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base class for all generators.
 */
abstract class BaseGenerator extends Command implements GeneratorInterface {

  /**
   * The command name.
   *
   * @var string
   */
  protected $name;

  /**
   * The command description.
   *
   * @var string
   */
  protected $description;

  /**
   * The command alias.
   *
   * @var string
   */
  protected $alias;

  /**
   * A path where templates are stored.
   *
   * @var string
   */
  protected $templatePath;

  /**
   * The working directory.
   *
   * @var string
   */
  protected $directory;

  /**
   * Files to create.
   *
   * The key of the each item in the array is the path to the file and
   * the value is the generated content of it.
   *
   * @var array
   */
  protected $files = [];

  /**
   * Services to dump.
   *
   * @var array
   */
  protected $services = [];

  /**
   * Hooks to dump.
   *
   * @var array
   */
  protected $hooks = [];

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName($this->name)
      ->setDescription($this->description)
      ->addOption(
        'directory',
        '-d',
        InputOption::VALUE_OPTIONAL,
        'Working directory'
      )
      ->addOption(
        'answers',
        '-a',
        InputOption::VALUE_OPTIONAL,
        'Default JSON formatted answers'
      );

    if ($this->alias) {
      $this->setAliases([$this->alias]);
    }

    if (!$this->templatePath) {
      $this->templatePath = DCG_ROOT . '/templates';
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function initialize(InputInterface $input, OutputInterface $output) {
    $this->getHelperSet()->setCommand($this);
    $this->getHelper('dcg_renderer')->addPath($this->templatePath);
    $this->directory = $input->getOption('directory') ?
      Utils::normalizePath($input->getOption('directory')) : getcwd();
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $dumped_files = $this->getHelper('dcg_dumper')->dump($input, $output);
    $this->getHelper('dcg_output_handler')->printSummary($output, $dumped_files);
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function getAssets() {
    return [
      'files' => $this->files,
      'hooks' => $this->hooks,
      'services' => $this->services,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setDirectory($directory) {
    $this->directory = $directory;
  }

  /**
   * {@inheritdoc}
   */
  public function getDirectory() {
    return $this->directory;
  }

  /**
   * Renders a template.
   *
   * @param string $template
   *   Twig template.
   * @param array $vars
   *   Template variables.
   *
   * @return string
   *   A string representing the rendered output.
   */
  protected function render($template, array $vars) {
    return $this->getHelper('dcg_renderer')->render($template, $vars);
  }

  /**
   * Asks the user for template variables.
   *
   * @param \Symfony\Component\Console\Input\InputInterface $input
   *   Input instance.
   * @param \Symfony\Component\Console\Output\OutputInterface $output
   *   Output instance.
   * @param array $questions
   *   List of questions that the user should answer.
   *
   * @return array
   *   Template variables.
   *
   * @see \DrupalCodeGenerator\InputHandler
   */
  protected function collectVars(InputInterface $input, OutputInterface $output, array $questions) {
    return $this->getHelper('dcg_input_handler')->collectVars($input, $output, $questions);
  }

}
