<?php namespace Config;

/***
 *
 * This file contains example values to alter default library behavior.
 * Recommended usage:
 *    1. Copy the file to app/Config/CodeigniterWebsocket.php
 *    2. Change any values
 *    3. Remove any lines to fallback to defaults
 *
 ***/

use CodeIgniter\Config\BaseConfig;

class CodeigniterWebsocket extends \Takielias\CodeigniterWebsocket\Config\CodeigniterWebsocket
{
    public $host = "0.0.0.0";
    public $port = 8282;
    public $timer = false;
    public $interval = 1;
    public $auth = false;
    public $debug = false;
    public $jwt_key = "GGFSRTSYTSOPLGCCXS";
    public $token_timeout = 1;
}
