<?php namespace ProcessWire;
/**
 * Nette Framework Integration for ProcessWire
 *
 * @author Bernhard Baumrock, 22.03.2020
 * @license Licensed under MIT
 * @link https://www.baumrock.com
 */
class RockNette extends WireData implements Module, ConfigurableModule {
  private $path;

  public static function getModuleInfo() {
    return [
      'title' => 'RockNette',
      'version' => '0.0.1',
      'summary' => 'Nette Framework Integration for ProcessWire',
      'autoload' => true,
      'singular' => true,
      'icon' => 'code',
      'requires' => [],
      'installs' => [],
    ];
  }

  public function init() {
    $path = $this->config->paths->assets . "nette";
    $this->files->mkdir($path);
    $this->path = $path;

    // prevent all direct access to this folder
    $htaccess = "$path/.htaccess";
    if(!is_file($htaccess)) {
      $this->files->filePutContents($htaccess, "deny from all");
    }

    // load nette
    if($this->autoload) $this->load();
  }

  /**
   * Load Nette
   * @return void
   */
  public function load() {
    $file = "{$this->path}/vendor/autoload.php";
    if(is_file($file)) require_once($file);
  }

  /**
   * Get all available packages
   * @return array
   */
  public function getPackages() {
    $packages = [];
    foreach (new \DirectoryIterator($this->path."/vendor/nette/") as $file) {
      if($file->isDot()) continue;
      $packages[] = $file->getBasename();
    }
    return $packages;
  }

  /**
  * Config inputfields
  * @param InputfieldWrapper $inputfields
  */
  public function getModuleConfigInputfields($inputfields) {
    $packages = implode(', ', $this->getPackages());
    $inputfields->add([
      'type' => 'checkbox',
      'name' => 'autoload',
      'label' => "Autoload available Nette Packages ($packages)",
      'checked' => $this->autoload ? 'checked' : '',
      'notes' => "If you don't autoload Nette, you have to load it manually: \$modules->get('RockNette')->load();",
    ]);
    $inputfields->add([
      'type' => 'markup',
      'label' => 'HowTo: Load Nette Packages',
      'icon' => 'cubes',
      'value' => "Go to <a href='https://nette.org/en/packages'>https://nette.org/en/packages</a> and find your package. Then install it via composer:"
        ."<br><pre>"
        ."cd {$this->path}<br>"
        ."composer require nette/forms"
        ."</pre>",
    ]);
    $inputfields->add([
      'type' => 'markup',
      'label' => 'Usage',
      'icon' => 'code',
      'value' => "Usage is exactly the same as shown in the Nette Docs:"
        ." eg <a href='https://github.com/nette/forms' target='_blank'>https://github.com/nette/forms</a>"
        ."<pre>use Nette\Forms\Form;<br>"
        .'$form = new Form;<br>'
        ."\$form->addText('name', 'Name:');<br>"
        ."\$form->addPassword('password', 'Password:');<br>"
        ."\$form->addSubmit('send', 'Register');<br>"
        ."echo \$form; // renders the form",
    ]);

    return $inputfields;
  }
}
