<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\NameMatcher;

use Yapte\TestCase;

use Yapte\Provider\Episode;

require_once __DIR__ . '/../TestCase.php';

/**
 * Tests for the RegExp name matcher
 *
 * @covers \Yapte\NameMatcher\RegExp
 * @group unittest
 */
class RegExpTest extends TestCase
{
    public function getNames()
    {
        return array(
            array(
                'BBC.The.Country.House.Revealed.5of6.Clandeboye.x264.AAC.HDTV.[MVGroup.org]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 6,
                ),
            ),
            array(
                'Family.Guy.S10E21.HDTV.x264-LOL.[eztv]',
                array(
                    'title' => null,
                    'season' => 10,
                    'episode' => 21,
                ),
            ),
            array(
                'How.I.Met.Your.Mother.7x23.(HDTV-x264-LOL)[VTV]',
                array(
                    'title' => null,
                    'season' => 7,
                    'episode' => 23,
                ),
            ),
            array(
                'The.Big.Bang.Theory.S05E24.HDTV.x264-LOL.[eztv]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 24,
                ),
            ),
            array(
                'The.Mentalist.4x23.(HDTV-x264-LOL)[VTV]',
                array(
                    'title' => null,
                    'season' => 4,
                    'episode' => 23,
                ),
            ),
            array(
                'True.Blood.S05E01.REPACK.720p.HDTV.x264-IMMERSE.[eztv]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 1,
                ),
            ),
            array(
                'Warehouse.13.S04E02.720p.HDTV.x264-COMPULSiON.[eztv]',
                array(
                    'title' => null,
                    'season' => 4,
                    'episode' => 2,
                ),
            ),
            array(
                'The Big Bang Theory - 218 - The Work Song Nanocluster.avi',
                array(
                    'title' => null,
                    'season' => 2,
                    'episode' => 18,
                ),
            ),
        );
    }

    /**
     * @dataProvider getNames
     */
    public function testParse($string, $episode)
    {
        $matcher = new RegExp();
        $this->assertEquals(
            new Episode($episode),
            $matcher->parse($string)
        );
    }
}
