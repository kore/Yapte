<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

require __DIR__ . '/TestCase.php';

/**
 * Tests for the Eztv provider
 *
 * @covers \Yapte\Provider\Eztv
 * @group unittest
 */
class EztvTest extends TestCase
{
    public function testGetShowList()
    {
        $provider = new Eztv($this->getHttpClient());
        $shows = $provider->getShowList();

        $this->assertTrue(is_array($shows));
        return $shows;
    }

    /**
     * @depends testGetShowList
     */
    public function testGetShowListNotEmpty($shows)
    {
        $this->assertTrue(count($shows) > 1);
    }

    /**
     * @depends testGetShowList
     */
    public function testGetShowListItemType($shows)
    {
        $this->assertTrue(
            array_reduce(
                array_map(
                    function ($show) {
                        return $show instanceof Show;
                    },
                    $shows
                ),
                function ($a, $b) {
                    return $a && $b;
                },
                true
            )
        );
    }
}
