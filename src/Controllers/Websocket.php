<?php

namespace Takielias\CodeigniterWebsocket\Controllers;

use CodeIgniter\Controller;

/**
 * @package   CodeIgniter WebSocket Library: Websocket Controller
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class Websocket extends Controller
{
    /**
     * @var Config
     */
    protected $config;

    public function __construct()
    {
        $this->config = config('CodeigniterWebsocket');
    }

    public function start()
    {
        $ws = service('CodeigniterWebsocket');
        $ws->set_callback('auth', array($this, '_auth'));
        $ws->set_callback('event', array($this, '_event'));
        $ws->run();
    }

    public function user($user_id = null)
    {
        return view('Websocket/websocket_message', array('user_id' => $user_id));
    }

    public function _auth($datas = null)
    {
        // Here you can verify everything you want to perform user login.

        return (!empty($datas->user_id)) ? $datas->user_id : false;
    }

    public function _event($datas = null)
    {
        // Here you can do everyting you want, each time message is received
        echo 'Hey ! I\'m an EVENT callback' . PHP_EOL;
    }
}
