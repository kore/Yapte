<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

require_once __DIR__ . '/TestCase.php';

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
        $shows = $provider->getShowList(array('The Mentalist'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'The Mentalist',
                    'internalId' => 'http://eztv.it/shows/179/the-mentalist/',
                ))
            ),
            $shows
        );
        return $shows;
    }

    public function testGetMultipleShows()
    {
        $provider = new Eztv($this->getHttpClient());
        $shows = $provider->getShowList(array('The Mentalist', 'House'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'The Mentalist',
                    'internalId' => 'http://eztv.it/shows/179/the-mentalist/',
                )),
                new Show(array(
                    'name' => 'House',
                    'internalId' => 'http://eztv.it/shows/124/house/',
                ))
            ),
            $shows
        );
        return $shows;
    }

    /**
     * @expectedException \OutOfRangeException
     */
    public function testGetShowVagueName()
    {
        $provider = new Eztv($this->getHttpClient());
        $provider->getShowList(array('The'));
    }
}
