<?php

namespace Takielias\CodeigniterWebsocket\Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\Config\BaseConfig;

/**
 * @package   CodeIgniter WebSocket Library: Default config service
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class Services extends BaseService
{
    public static function CodeigniterWebsocket(BaseConfig $config = null, bool $getShared = true)
    {
        if ($getShared):
            return static::getSharedInstance('CodeigniterWebsocket', $config);
        endif;

        // If no config was injected then load one
        // Prioritizes app/Config if found
        if (empty($config))
            $config = config('CodeigniterWebsocket');

        return new \Takielias\CodeigniterWebsocket\Libraries\CodeigniterWebsocket($config);
    }
}
