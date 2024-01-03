<?php

namespace Takielias\CodeigniterWebsocket\Commands;

use Config\Autoload;
use CodeIgniter\CLI\CLI;
use CodeIgniter\CLI\BaseCommand;

/**
 * @package   CodeIgniter WebSocket Library: Resource Publishing Command
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class Publish extends BaseCommand
{
    /**
     * The group the command is lumped under
     * when listing commands.
     *
     * @var string
     */
    protected $group = 'Websocket';

    /**
     * The Command's name
     *
     * @var string
     */
    protected $name = 'websocket:publish';

    /**
     * the Command's short description
     *
     * @var string
     */
    protected $description = 'Publish selected Websocket functionality into the current application.';

    /**
     * the Command's usage
     *
     * @var string
     */
    protected $usage = 'websocket:publish';

    /**
     * the Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * the Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * The path to Takielias\CodeigniterWebsocket\src directory.
     *
     * @var string
     */
    protected $sourcePath;

    //--------------------------------------------------------------------

    /**
     * Displays the help for the spark cli script itself.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $this->determineSourcePath();

        // Controller
        if (CLI::prompt('Publish Controller?', ['y', 'n']) == 'y') {
            $this->publishController();
        }

        // Views
        if (CLI::prompt('Publish Views?', ['y', 'n']) == 'y') {
            $this->publishViews();
        }

        // Config
        if (CLI::prompt('Publish Config file?', ['y', 'n']) == 'y') {
            $this->publishConfig();
        }

        // Language
        if (CLI::prompt('Publish Language file?', ['y', 'n']) == 'y') {
            $this->publishLanguage();
        }
    }

    protected function publishController()
    {
        $path = "{$this->sourcePath}/Controllers/Websocket.php";
        $content = file_get_contents($path);
        $content = $this->replaceNamespace($content, 'Takielias\CodeigniterWebsocket\Controllers', 'Controllers');
        $this->writeFile("Controllers/Websocket.php", $content);
    }

    protected function publishViews()
    {
        $map = directory_map($this->sourcePath . '/Views');
        $prefix = '';

        foreach ($map as $key => $view) {
            if (is_array($view)) {
                $oldPrefix = $prefix;
                $prefix .= $key;

                foreach ($view as $file) {
                    $this->publishView($file, $prefix);
                }

                $prefix = $oldPrefix;

                continue;
            }

            $this->publishView($view, $prefix);
        }
    }

    protected function publishView($view, string $prefix = '')
    {
        $path = "{$this->sourcePath}/Views/{$prefix}{$view}";
        $namespace = defined('APP_NAMESPACE') ? APP_NAMESPACE : 'App';

        $content = file_get_contents($path);
        $content = str_replace('Websocket\Views', $namespace . '\Websocket', $content);

        $this->writeFile("Views/Websocket/{$prefix}{$view}", $content);
    }

    protected function publishConfig()
    {
        $path = "{$this->sourcePath}/Config/CodeigniterWebsocket.php";

        $content = file_get_contents($path);
        $content = str_replace('namespace Takielias\CodeigniterWebsocket\Config', "namespace Config", $content);
        $content = str_replace('extends BaseConfig', "extends \Takielias\CodeigniterWebsocket\Config\CodeigniterWebsocket", $content);

        $this->writeFile("Config/CodeigniterWebsocket.php", $content);
    }

    protected function publishLanguage()
    {
        $path = "{$this->sourcePath}/Language/en/CodeigniterWebsocket.php";

        $content = file_get_contents($path);

        $this->writeFile("Language/en/CodeigniterWebsocket.php", $content);
    }

    //--------------------------------------------------------------------
    // Utilities
    //--------------------------------------------------------------------

    /**
     * Replaces the Takielias\CodeigniterWebsocket namespace in the published
     * file with the applications current namespace.
     *
     * @param string $contents
     * @param string $originalNamespace
     * @param string $newNamespace
     *
     * @return string
     */
    protected function replaceNamespace(string $contents, string $originalNamespace, string $newNamespace): string
    {
        $appNamespace = APP_NAMESPACE;
        $originalNamespace = "namespace {$originalNamespace}";
        $newNamespace = "namespace {$appNamespace}\\{$newNamespace}";

        return str_replace($originalNamespace, $newNamespace, $contents);
    }

    /**
     * Determines the current source path from which all other files are located.
     */
    protected function determineSourcePath()
    {
        $this->sourcePath = realpath(__DIR__ . '/../');

        if ($this->sourcePath == '/' || empty($this->sourcePath)) {
            CLI::error('Unable to determine the correct source directory. Bailing.');
            exit();
        }
    }

    /**
     * Write a file, catching any exceptions and showing a
     * nicely formatted error.
     *
     * @param string $path
     * @param string $content
     */
    protected function writeFile(string $path, string $content)
    {
        $config = new Autoload();
        $appPath = $config->psr4[APP_NAMESPACE];

        $directory = dirname($appPath . $path);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        try {
            write_file($appPath . $path, $content);
        } catch (\Exception $e) {
            $this->showError($e);
            exit();
        }

        $path = str_replace($appPath, '', $path);

        CLI::write(CLI::color('  created: ', 'green') . $path);
    }
}
