<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

require_once __DIR__ . '/TestCase.php';

/**
 * Tests for the TheTVDB provider
 *
 * @covers \Yapte\Provider\TheTVDB
 * @group unittest
 */
class TheTVDBTest extends TestCase
{
    public function testGetShowListSingleItem()
    {
        $provider = new TheTVDB(
            $this->getHttpClient(),
            $this->getTestConfiguration()->thetvdb['apiKey']
        );

        $shows = $provider->getShowList(array('The Mentalist'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'The Mentalist',
                    'internalId' => '82459',
                ))
            ),
            $shows
        );
        return $shows;
    }

    public function testGetShowInvalidCase()
    {
        $provider = new TheTVDB(
            $this->getHttpClient(),
            $this->getTestConfiguration()->thetvdb['apiKey']
        );

        $shows = $provider->getShowList(array('the mentalist'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'The Mentalist',
                    'internalId' => '82459',
                ))
            ),
            $shows
        );
        return $shows;
    }

    public function testGetMultipleShows()
    {
        $provider = new TheTVDB(
            $this->getHttpClient(),
            $this->getTestConfiguration()->thetvdb['apiKey']
        );

        $shows = $provider->getShowList(array('The Mentalist', 'House'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'The Mentalist',
                    'internalId' => '82459',
                )),
                new Show(array(
                    'name' => 'House',
                    'internalId' => '73255',
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
        $provider = new TheTVDB(
            $this->getHttpClient(),
            $this->getTestConfiguration()->thetvdb['apiKey']
        );

        $provider->getShowList(array('The'));
    }

    /**
     * @depends testGetShowListSingleItem
     */
    public function testGetEpisodes($shows)
    {
        $provider = new TheTVDB(
            $this->getHttpClient(),
            $this->getTestConfiguration()->thetvdb['apiKey']
        );
        $episodes = $provider->getEpisodeList($shows[0]);

        $this->assertTrue(is_array($episodes));
        return $episodes;
    }

    /**
     * @depends testGetEpisodes
     */
    public function testGetEpisodeTypes(array $episodes)
    {
        $this->assertTrue(
            array_reduce(
                array_map(
                    function ($show) {
                        return $show instanceof Episode;
                    },
                    $episodes
                ),
                function ($a, $b) {
                    return $a && $b;
                },
                true
            )
        );

        return $episodes;
    }

    public function getEpisodeValues()
    {
        return array(
            array('title', 'Pilot'),
            array('season', 1),
            array('episode', 1),
        );
    }

    /**
     * @depends testGetEpisodeTypes
     * @dataProvider getEpisodeValues
     */
    public function testGetEpisodeValues($key, $value, array $episodes)
    {
        $this->assertEquals(
            $value,
            $episodes[0]->$key
        );
    }
}
