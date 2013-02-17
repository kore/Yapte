<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use org\bovigo\vfs\vfsStream;

require_once __DIR__ . '/TestCase.php';

/**
 * Tests for the FileSystem provider
 *
 * @covers \Yapte\Provider\FileSystem
 * @group unittest
 */
class FileSystemTest extends TestCase
{
    /**
     * Default test fixture
     *
     * @var array
     */
    protected $testFixture = array(
        'House/Season 6/House [6x12] Moving the Chains.avi',
        'House/Season 6/House [6x15] Black Hole.avi',
        'The Mentalist/Season 3/The Mentalist [3x07] Red Hot .avi',
        'The Mentalist/Season 3/The Mentalist [3x22] Rhapsody in Red.avi',
        'The Mentalist/Season 3/The Mentalist [3x12] Bloodhounds.avi',
    );

    protected function getProvider()
    {
        vfsStream::setup("test");
        $basedir = vfsStream::url("test") . '/';

        foreach ($this->testFixture as $fileName) {
            $fileName = $basedir . $fileName;
            $directory = dirname($fileName);
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }
            touch($fileName);
        }

        return new FileSystem($basedir);
    }

    public function testGetShowListSingleItem()
    {
        $provider = $this->getProvider();
        $shows = $provider->getShowList(array('The Mentalist'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'The Mentalist',
                    'internalId' => 'vfs://test/The Mentalist/',
                ))
            ),
            $shows
        );
        return $shows;
    }

    public function testGetShowInvalidCase()
    {
        $provider = $this->getProvider();
        $shows = $provider->getShowList(array('the mentalist'));

        $this->assertEquals(
            array(
                new Show(array(
                    'name' => 'the mentalist',
                    'internalId' => 'vfs://test/The Mentalist/',
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
                    'internalId' => 'vfs://test/The Mentalist/',
                )),
                new Show(array(
                    'name' => 'House',
                    'internalId' => 'vfs://test/House/',
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
