<?php
/**
 * @package   CodeIgniter WebSocket Library: Codeigniter Websocket Helper
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
use CodeIgniter\CLI\CLI;
use Takielias\CodeigniterWebsocket\Libraries\AUTHORIZATION;

if (!function_exists('valid_json')) {

    /**
     * Check JSON validity
     * @method valid_json
     * @param mixed $var Variable to check
     * @return bool
     */
    function valid_json($var)
    {
        return (is_string($var)) && (is_array(json_decode($var,
            true))) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}

if (!function_exists('valid_jwt')) {

    /**
     * Check JWT validity
     * @method valid_jwt
     * @param mixed $token Variable to check
     * @return Object/false
     */
    function valid_jwt($token)
    {
        return AUTHORIZATION::validateToken($token);
    }
}

/**
 * Codeigniter Websocket Library: helper file
 */
if (!function_exists('output')) {

    /**
     * Output valid or invalid logs
     * @method output
     * @param string $type Log type
     * @param string $var String
     * @return string
     */
    function output($type = 'success', $output = null)
    {
        if ($type == 'success') {
            CLI::write($output, 'green');
        } elseif ($type == 'error') {
            CLI::write($output, 'red');
        } elseif ($type == 'fatal') {
            CLI::write($output, 'red');
            exit(EXIT_ERROR);
        } else {
            CLI::write($output, 'green');
        }
    }
}
