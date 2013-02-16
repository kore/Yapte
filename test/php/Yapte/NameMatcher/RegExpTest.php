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
                'Futurama.S07E08.Fun.on.a.Bun.HDTV.x264-FQM.[eztv]',
                array(
                    'title' => null,
                    'season' => 7,
                    'episode' => 8,
                ),
            ),
            array(
                'House.S08E22.720p.HDTV.x264-DIMENSION.[eztv]',
                array(
                    'title' => null,
                    'season' => 8,
                    'episode' => 22,
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
                'Leverage.S05E04.HDTV.x264-ASAP.[eztv]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 4,
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
                'The.Simpsons.S23E21.HDTV.x264-LOL.[eztv]',
                array(
                    'title' => null,
                    'season' => 23,
                    'episode' => 21,
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
                'True.Blood.S05E01.REPACK.HDTV.x264-ASAP.[eztv]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 1,
                ),
            ),
            array(
                'True.Blood.S05E11.HDTV.x264-EVOLVE.[eztv]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 11,
                ),
            ),
            array(
                'True.Blood.S05E12.720p.HDTV.x264-EVOLVE.[eztv]',
                array(
                    'title' => null,
                    'season' => 5,
                    'episode' => 12,
                ),
            ),
            array(
                'Warehouse.13.4x04.(HDTV-x264-KILLERS)[VTV]',
                array(
                    'title' => null,
                    'season' => 4,
                    'episode' => 4,
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
