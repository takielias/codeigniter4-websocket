<?php

namespace Takielias\CodeigniterWebsocket\Exceptions;

use CodeIgniter\Exceptions\ExceptionInterface;
use CodeIgniter\Exceptions\FrameworkException;

/**
 * @package   CodeIgniter WebSocket Library: Exception handleing Class
 * @category  Libraries
 * @author    Taki Elias <taki.elias@gmail.com>
 * @license   http://opensource.org/licenses/MIT > MIT License
 * @link      https://github.com/takielias
 *
 * CodeIgniter WebSocket library. It allows you to make powerful realtime applications by using Ratchet Websocket
 */
class CodeigniterWebsocketException extends FrameworkException implements ExceptionInterface
{
    public static function forMissingName()
    {
        return new static(lang('CodeigniterWebscket.missingName'));
    }

    public static function forUnmatchedName(string $name)
    {
        return new static(lang('CodeigniterWebscket.unmatchedName', [$name]));
    }

    public static function forProtectionViolation(string $name)
    {
        return new static(lang('CodeigniterWebscket.protectionViolation', [$name]));
    }
}
