<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\MetaDataExtractor;

use Yapte\MetaDataExtractor;
use Yapte\Provider\MetaData;

/**
 * Base meta data extractor
 *
 * @version $Revision$
 */
class RegExp extends MetaDataExtractor
{
    /**
     * Array of common quality attributes found in file names
     *
     * @var array
     */
    protected $quality = array(
        '(1080p)i' => 1080,
        '(1080i)i' => 1079,
        '(720p)i' => 720,
        '(720i)i' => 719,
        '(hdtv)i' => 480,
    );

    /**
     * Array of common version identifiers found in file names
     *
     * @var array
     */
    protected $version = array(
        '(repack)i' => 1,
        '(proper)i' => 1,
    );

    /**
     * Array of video codecs
     *
     * @var array
     */
    protected $videoCodec = array(
        '(xvid)i' => 'XVid',
        '(x264)i' => 'x264',
        '(divx)i' => 'divx',
    );

    /**
     * Array of audio codecs
     *
     * @var array
     */
    protected $audioCodec = array(
        '(mp3)i' => 'mp3',
        '(aac)i' => 'aac',
        '(ac3)i' => 'ac3',
    );

    /**
     * Try to extract meta data from string
     *
     * @param string $string
     * @return MetaData
     */
    public function getMetaData($string)
    {
        $metaData = new MetaData();
        foreach ($metaData as $property => $value) {
            foreach ($this->$property as $regExp => $value) {
                if (preg_match($regExp, $string)) {
                    $metaData->$property = $value;
                    break;
                }
            }
        }

        return $metaData;
    }
}
