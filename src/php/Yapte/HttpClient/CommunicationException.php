<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\HttpClient;

/**
 * Communication exception
 *
 * @version $Revision$
 */
class CommunicationException extends \RuntimeException
{
    public function __construct($server, $path, $method, $parentException = null)
    {
        parent::__construct(
            "Could not reach $server with $method $path.",
            0,
            $parentException
        );
    }
}
