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
    public function __construct($url, $method, $parentException = null)
    {
        parent::__construct(
            "Could not reach server under $method $url.",
            0,
            $parentException
        );
    }
}
