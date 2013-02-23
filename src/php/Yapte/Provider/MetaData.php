<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Struct;

/**
 * Provider base class
 *
 * @version $Revision$
 */
class MetaData extends Struct
{
    /**
     * Quality of torrent (usually horizontal resolution)
     *
     * @var int
     */
    public $quality = 320;

    /**
     * Release version of torrent
     *
     * @var int
     */
    public $version = 0;

    /**
     * Used video codec
     *
     * @var string
     */
    public $videoCodec = null;

    /**
     * Used audio codec
     *
     * @var string
     */
    public $audioCodec = null;
}
