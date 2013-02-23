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
class Torrent extends Struct
{
    /**
     * Torrent URL
     *
     * @var string
     */
    public $url;

    /**
     * Torrent meta data
     *
     * @var MetaData
     */
    public $metaData;
}
