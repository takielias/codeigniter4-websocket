<?php

namespace Takielias\CodeigniterWebsocket\Libraries;

use CodeIgniter\Config\BaseConfig;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

/**
 * @package   CodeIgniter WebSocket Library: Main class
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class CodeigniterWebsocket
{

    /**
     * Our configuration instance.
     *
     * @var \Takielias\CodeigniterWebsocket\Config\
     */
    protected $config;
    /**
     * The setting model used to fetch Settings templates.
     *
     * @var \Takielias\CodeigniterWebsocket\Models\
     */
    protected $model;

    /**
     * Default host var
     * @var string
     */
    public $host = null;

    /**
     * Default port var
     * @var string
     */
    public $port = null;

    /**
     * Default auth var
     * @var bool
     */
    public $auth = false;

    /**
     * Default debug var
     * @var bool
     */
    public $debug = false;

    /**
     * Auth callback information
     * @var array
     */
    public $callback = array();

    /**
     * Class Constructor
     * @method __construct
     * @param array $config Configuration
     * @return void
     */

    // initiate library, check for existing Configuration
    public function __construct(BaseConfig $config)
    {
        helper('codeigniter_websocket');
        // save configuration
        $this->config = $config;
    }

    /**
     * Launch the server
     * @method run
     * @return string
     */
    public function run()
    {
        // Initialize all the necessary class
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new Server($this->config, $this->callback)
                )
            ),
            $this->config->port,
            $this->config->host
        );

        // Output
        if ($this->config->debug) {
            output('success',
                'Running server on host ' . $this->config->host . ':' . $this->config->port);
        }

        //If you want to use timer

        if ($this->config->timer) {
            $server->loop->addPeriodicTimer($this->config->interval, function () {
                if (!empty($this->callback['citimer'])) {
                    call_user_func_array($this->callback['citimer'], array(date('d-m-Y h:i:s a', time())));
                }
            });

        }

        // Run the socket connection

        $server->run();
    }

    /**
     * Define a callback to use auth or event callback
     * @method set_callback
     * @param array $callback
     * @return void
     */
    public function set_callback($type = null, array $callback = array())
    {
        // Check if we have an authorized callback given
        if (!empty($type) && in_array($type, $this->config->callbacks)) {
            // Verify if the method does really exists
            if (is_callable($callback)) {
                // Register callback as class var
                $this->callback[$type] = $callback;
            } else {
                output('fatal', 'Method ' . $callback[1] . ' is not defined');
            }
        }
    }
}

