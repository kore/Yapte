<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

use Yapte\HttpClient;

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
        return array();
    }
}
