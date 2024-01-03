<?php

namespace Takielias\CodeigniterWebsocket\Libraries;

use CodeIgniter\Config\BaseConfig;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Takielias\CodeigniterWebsocket\Libraries\Authorization;

/**
 * @package   CodeIgniter WebSocket Library: Server class
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket Server library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class Server implements MessageComponentInterface
{
    /**
     * List of connected clients
     * @var array
     */
    public $clients;

    /**
     * List of subscribers (associative array)
     * @var array
     */
    protected $subscribers = array();

    /**
     * Class constructor
     * @method __construct
     */


    /**
     * Our configuration instance.
     *
     * @var \Tatter\Settings\Config\Settings
     */
    protected $config;
    /**
     * The active user session.
     *
     * @var \CodeIgniter\Session\Session
     */
    protected $CI;

    /**
     * The setting model used to fetch Settings templates.
     *
     * @var \Tatter\Settings\Models\SettingModel
     */
    protected $model;

    /**
     * Default host var
     * @var string
     */
    public $host = null;

    /**
     * Default host var
     * @var string
     */
    public $port = null;

    /**
     * Default auth var
     * @var bool
     */
    public $auth = false;

    /**
     * Default Timer Interval var
     * @var bool
     */
    public $timer_interval = 1;

    /**
     * Default debug var
     * @var bool
     */
    public $debug = false;

    /**
     * Auth callback informations
     * @var array
     */
    public $callback = array();

    /**
     * Config vars
     * @var array
     */
    /**
     * Define allowed callbacks
     * @var array
     */
    protected $callback_type = array('auth', 'event', 'close', 'citimer', 'roomjoin', 'roomleave', 'roomchat');

    /**
     * Class Constructor
     * @method __construct
     * @param array $config Configuration
     * @return void
     */


    public function __construct(BaseConfig $config, $callback)
    {
        // Initialize object as SplObjectStorage (see PHP doc)
        $this->clients = new \SplObjectStorage();

        helper('codeigniter_websocket');

        // save configuration
        $this->config = $config;

        $this->callback = $callback;

        // // Check if auth is required
        if ($this->config->auth && empty($this->callback['auth'])) {
            output('fatal', 'Authentication callback is required, you must set it before run server, aborting..');
        }

        // Output
        if ($this->config->debug) {
            output('success',
                'Running server on host ' . $this->config->host . ':' . $this->config->port);
        }

        // Output
        if (!empty($this->callback['auth']) && $this->config->debug) {
            output('success', 'Authentication activated');
        }

        // Output
        if (!empty($this->callback['close']) && $this->config->debug) {
            output('success', 'Close activated');
        }

    }

    /**
     * Event trigerred on new client event connection
     * @method onOpen
     * @param ConnectionInterface $connection
     * @return string
     */
    public function onOpen(ConnectionInterface $connection)
    {
        // Add client to global clients object
        $this->clients->attach($connection);

        // Output
        if ($this->config->debug) {
            output('info', 'New client connected as (' . $connection->resourceId . ')');
        }
    }

    /**
     * Event trigerred on new message sent from client
     * @method onMessage
     * @param ConnectionInterface $client
     * @param string $message
     * @return string
     */
    public function onMessage(ConnectionInterface $client, $message)
    {
        // Broadcast var
        $broadcast = false;

        // Check if received var is json format
        if (valid_json($message)) {
            // If true, we have to decode it
            $datas = json_decode($message);

            // Once we decoded it, we check look for global broadcast
            $broadcast = (!empty($datas->broadcast) and $datas->broadcast == true) ? true : false;

            // Count real clients numbers (-1 for server)
            $clients = count($this->clients) - 1;

            // Here we have to reassign the client ressource ID, this will allow us to send message to specified client.

            if (!empty($datas->type) && $datas->type == 'socket') {

                if (!empty($this->callback['auth'])) {

                    // Call user personal callback
                    $auth = call_user_func_array($this->callback['auth'],
                        array($datas));

                    // Verify authentication

                    if (empty($auth) or !is_integer($auth)) {
                        output('error', 'Client (' . $client->resourceId . ') authentication failure');
                        $client->send(json_encode(array("type" => "error", "msg" => 'Invalid ID or Password.')));
                        // Closing client connexion with error code "CLOSE_ABNORMAL"
                        $client->close(1006);
                    }

                    // Add UID to associative array of subscribers
                    $client->subscriber_id = $auth;

                    if ($this->config->auth) {
                        $data = json_encode(array("type" => "token", "token" => Authorization::generateToken($client->resourceId)));
                        $this->send_message($client, $data, $client);
                    }

                    // Output
                    if ($this->config->debug) {
                        output('success', 'Client (' . $client->resourceId . ') authentication success');
                        output('success', 'Token : ' . Authorization::generateToken($client->resourceId));
                    }
                }

            }


            if (!empty($datas->type) && $datas->type == 'roomjoin') {
                if (valid_jwt($datas->token) != false) {
                    if (!empty($this->callback['roomjoin'])) {
                        // Call user personal callback
                        call_user_func_array($this->callback['roomjoin'],
                            array($datas, $client));
                    }

                } else {
                    $client->send(json_encode(array("type" => "error", "msg" => 'Invalid Token.')));
                }

            }

            if (!empty($datas->type) && $datas->type == 'roomleave') {

                if (valid_jwt($datas->token) != false) {

                    if (!empty($this->callback['roomleave'])) {

                        // Call user personal callback
                        call_user_func_array($this->callback['roomleave'],
                            array($datas, $client));

                    }


                } else {

                    $client->send(json_encode(array("type" => "error", "msg" => 'Invalid Token.')));
                }

            }

            if (!empty($datas->type) && $datas->type == 'roomchat') {

                if (valid_jwt($datas->token) != false) {

                    if (!empty($this->callback['roomchat'])) {

                        // Call user personal callback
                        call_user_func_array($this->callback['roomchat'],
                            array($datas, $client));
                    }

                } else {

                    $client->send(json_encode(array("type" => "error", "msg" => 'Invalid Token.')));
                }

            }


            // Now this is the management of messages destinations, at this moment, 4 possibilities :
            // 1 - Message is not an array OR message has no destination (broadcast to everybody except us)
            // 2 - Message is an array and have destination (broadcast to single user)
            // 3 - Message is an array and don't have specified destination (broadcast to everybody except us)
            // 4 - Message is an array and we wan't to broadcast to ourselves too (broadcast to everybody)

            if (!empty($datas->type) && $datas->type == 'chat') {

                $pass = true;

                if ($this->config->auth) {

                    if (!valid_jwt($datas->token)) {
                        output('error', 'Client (' . $client->resourceId . ') authentication failure. Invalid Token');
                        $client->send(json_encode(array("type" => "error", "msg" => 'Invalid Token.')));
                        // Closing client connexion with error code "CLOSE_ABNORMAL"
                        $client->close(1006);
                        $pass = false;
                    }
                }

                if ($pass) {
                    if (!empty($message)) {

                        // We look arround all clients
                        foreach ($this->clients as $user) {

                            // Broadcast to single user
                            if (!empty($datas->recipient_id)) {
                                if ($user->subscriber_id == $datas->recipient_id) {
                                    $this->send_message($user, $message, $client);
                                    break;
                                }
                            } else {
                                // Broadcast to everybody
                                if ($broadcast) {
                                    $this->send_message($user, $message, $client);
                                } else {
                                    // Broadcast to everybody except us
                                    if ($client !== $user) {
                                        $this->send_message($user, $message, $client);
                                    }
                                }
                            }
                        }
                    }
                }

            }

        } else {
            output('error', 'Client (' . $client->resourceId . ') Invalid json.');
            // Closing client connexion with error code "CLOSE_ABNORMAL"
            $client->close(1006);
        }

    }

    /**
     * Event triggered when connection is closed (or user disconnected)
     * @method onClose
     * @param ConnectionInterface $connection
     * @return string
     */
    public function onClose(ConnectionInterface $connection)
    {
        // Output
        if ($this->config->debug) {
            output('info', 'Client (' . $connection->resourceId . ') disconnected');
        }

        if (!empty($this->callback['close'])) {
            call_user_func_array($this->callback['close'], array($connection));
        }
        // Detach client from SplObjectStorage
        $this->clients->detach($connection);
    }

    /**
     * Event trigerred when error occured
     * @method onError
     * @param ConnectionInterface $connection
     * @param Exception $e
     * @return string
     */
    public function onError(ConnectionInterface $connection, \Exception $e)
    {
        // Output
        if ($this->config->debug) {
            output('fatal', 'An error has occurred: ' . $e->getMessage());
        }

        // We close this connection
        $connection->close();
    }

    /**
     * Function to send the message
     * @method send_message
     * @param array $user User to send
     * @param array $message Message
     * @param array $client Sender
     * @return string
     */
    protected function send_message($user = array(), $message = array(), $client = array())
    {
        // Send the message
        $user->send($message);

        // We have to check if event callback must be called
        if (!empty($this->callback['event'])) {

            // At this moment we have to check if we have authent callback defined
            call_user_func_array($this->callback['event'],
                array((valid_json($message) ? json_decode($message) : $message)));

            // Output
            if ($this->config->debug) {
                output('info', 'Callback event "' . $this->callback['event'][1] . '" called');
            }
        }

        // Output
        if ($this->config->debug) {
            output('info',
                'Client (' . $client->resourceId . ') send \'' . $message . '\' to (' . $user->resourceId . ')');
        }
    }

}