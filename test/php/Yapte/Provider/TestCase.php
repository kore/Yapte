<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\HttpClient;

require_once __DIR__ . '/../TestCase.php';

/**
 * Base test case for providers
 */
abstract class TestCase extends \Yapte\TestCase
{
    /**
     * Get test HTTP Client
     *
     * @return HttpClient
     */
    protected function getHttpClient()
    {
        return new HttpClient\Caching(
            new HttpClient\Stream(),
            __DIR__ . '/_dump/',
            86400
        );
    }
}
