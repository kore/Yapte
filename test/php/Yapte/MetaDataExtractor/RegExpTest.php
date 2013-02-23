<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\MetaDataExtractor;

use Yapte\TestCase;

use Yapte\Provider\MetaData;

require_once __DIR__ . '/../TestCase.php';

/**
 * Tests for the RegExp meta data extractor
 *
 * @covers \Yapte\MetaDataExtractor\RegExp
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
                    'quality' => 480,
                    'version' => 0,
                    'videoCodec' => 'x264',
                    'audioCodec' => 'aac',
                ),
            ),
            array(
                'Family.Guy.S10E21.HDTV.x264-LOL.[eztv]',
                array(
                    'quality' => 480,
                    'version' => 0,
                    'videoCodec' => 'x264',
                    'audioCodec' => null,
                ),
            ),
            array(
                'True.Blood.S05E01.REPACK.720p.HDTV.x264-IMMERSE.[eztv]',
                array(
                    'quality' => 720,
                    'version' => 1,
                    'videoCodec' => 'x264',
                    'audioCodec' => null,
                ),
            ),
            array(
                'The Mentalist S01E17 Carnelian Inc PROPER HDTV XviD-FQM.avi',
                array(
                    'quality' => 480,
                    'version' => 1,
                    'videoCodec' => 'XVid',
                    'audioCodec' => null,
                ),
            ),
            array(
                ' The Mentalist S01E15 REPACK HDTV XviD-XOR.mpeg',
                array(
                    'quality' => 480,
                    'version' => 1,
                    'videoCodec' => 'XVid',
                    'audioCodec' => null,
                ),
            ),
        );
    }

    /**
     * @dataProvider getNames
     */
    public function testParse($string, $data)
    {
        $matcher = new RegExp();
        $this->assertEquals(
            new MetaData($data),
            $matcher->getMetaData($string)
        );
    }
}
