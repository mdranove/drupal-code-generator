<?php

namespace DrupalCodeGenerator\Command\Drupal_7;

use DrupalCodeGenerator\Command\ThemeGenerator;

/**
 * Implements d7:theme-info command.
 */
class ThemeInfo extends ThemeGenerator {

  protected $name = 'd7:theme-info';
  protected $description = 'Generates info file for a Drupal 7 theme';
  protected $label = 'Info (theme)';

  /**
   * {@inheritdoc}
   */
  protected function generate() :void {
    $vars = &$this->collectDefault();
    $vars['description'] = $this->ask('Theme description', 'A simple Drupal 7 theme.');
    $vars['base_theme'] = $this->ask('Base theme');
    $this->addFile('{machine_name}.info', 'd7/theme-info');
  }

}
