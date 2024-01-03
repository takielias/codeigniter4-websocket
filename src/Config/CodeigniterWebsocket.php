<?php

namespace Takielias\CodeigniterWebsocket\Config;

use CodeIgniter\Config\BaseConfig;

/**
 * @package   CodeIgniter WebSocket Library: Config file
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class CodeigniterWebsocket extends BaseConfig
{
    public $host = "0.0.0.0";
    public $port = 8282;
    public $timer = false;
    public $interval = 1;
    public $auth = false;
    public $debug = false;
    public $jwt_key = "GGFSRTSYTSOPLGCCXS";
    public $token_timeout = 1;

    public $callbacks = ['auth', 'event', 'close', 'citimer', 'roomjoin', 'roomleave', 'roomchat'];
}
