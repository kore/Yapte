<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\HttpClient;

/**
 * Provider base class
 *
 * @version $Revision$
 */
class Message
{
    /**
     * Response headers
     *
     * @var array
     */
    public $headers;

    /**
     * Response body
     *
     * @var string
     */
    public $body;

    /**
     * Construct from headers and body
     *
     * @param array $headers
     * @param string $body
     *
     * @return void
     */
    public function __construct(array $headers = array(), $body = '')
    {
        $this->headers = $headers;
        $this->body    = $body;
    }
}
