<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

/**
 * Basic HTTP client
 *
 * @version $Revision$
 */
abstract class HttpClient
{
    /**
     * Execute a HTTP request to the remote server
     *
     * Returns the result from the remote server.
     *
     * @param string $method
     * @param string $path
     * @param HttpClient\Message $message
     *
     * @return HttpClient\Message
     */
    abstract public function request($method, $path, Message $message = null);
}
