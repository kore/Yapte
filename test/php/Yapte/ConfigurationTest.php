<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

require_once __DIR__ . '/TestCase.php';

/**
 * Test case for configuration files
 */
class ConfigurationTest extends TestCase
{
    public function getConfigurations()
    {
        return array_map(
            function ($file) {
                return array($file, $file . '.php');
            },
            glob(__DIR__ . '/_fixtures/*.ini')
        );
    }

    /**
     * @dataProvider getConfigurations
     */
    public function testReadConfiguration($file, $result)
    {
        $configuration = new Configuration($file, 'development');

        if (!file_exists($result)) {
            if (getenv("DUMP")) {
                file_put_contents(
                    $result,
                    "<?php\n\nreturn " . var_export($configuration->getAsArray(), true) . ";\n"
                );
            }

            $this->markTestSkipped("Expectation not available yet.");
        }

        $this->assertEquals(
            include $result,
            $configuration->getAsArray()
        );
    }

    public function testGetter()
    {
        $configuration = new Configuration(__DIR__ . '/_fixtures/01_basic.ini', 'development');
        $this->assertEquals(
            'bar',
            $configuration->foo
        );
    }

    /**
     * @expectedException \OutOfBoundsException
     */
    public function testGetNotExisting()
    {
        $configuration = new Configuration(__DIR__ . '/_fixtures/01_basic.ini', 'development');
        $configuration->notExisting;
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testParseInvalidEnvironment()
    {
        new Configuration(__DIR__ . '/_fixtures/01_basic.ini', 'invalid');
    }
}
