<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\HttpClient;

/**
 * Tests for the Eztv provider
 *
 * @covers \Yapte\Provider\Eztv
 * @group unittest
 */
class EztvTest extends \PHPUnit_Framework_TestCase
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

    public function testFoo()
    {
        $provider = new Eztv($this->getHttpClient());
    }
}
