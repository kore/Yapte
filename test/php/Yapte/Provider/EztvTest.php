<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\NameMatcher;
use Yapte\MetaDataExtractor;

require_once __DIR__ . '/TestCase.php';

/**
 * Tests for the Eztv provider
 *
 * @covers \Yapte\Provider\Eztv
 * @group unittest
 */
class EztvTest extends TestCase
{
    protected function getProvider()
    {
        return new Eztv(
            $this->getHttpClient(),
            new NameMatcher\RegExp(),
            new MetaDataExtractor\RegExp()
        );
    }

    public function testGetShowListSingleItem()
    {
        $provider = $this->getProvider();
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
        $provider = $this->getProvider();
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
        $provider = $this->getProvider();
        $provider->getShowList(array('The'));
    }

    /**
     * @depends testGetShowListSingleItem
     */
    public function testGetEpisodes($shows)
    {
        $provider = $this->getProvider();
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
            array('title', null),
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
