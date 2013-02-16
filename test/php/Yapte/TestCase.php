<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

use Yapte\Configuration;

/**
 * Base test case
 */
abstract class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Get test configuration
     *
     * @return array
     */
    protected function getTestConfiguration()
    {
        $configuration = new Configuration(__DIR__ . '/../../../src/config/configuration.ini', 'testing');
        return $configuration;
    }
}
