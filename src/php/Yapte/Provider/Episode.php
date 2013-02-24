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
 * @TODO: Split in file and episode structs?
 *
 * @version $Revision$
 */
class Episode extends Struct
{
    /**
     * Internal episode ID
     *
     * @var mixed
     */
    public $internalId;

    /**
     * Episode title
     *
     * @var string
     */
    public $title;

    /**
     * Season
     *
     * @var int
     */
    public $season;

    /**
     * Episode
     *
     * @var int
     */
    public $episode;

    /**
     * Torrents
     *
     * @var Torrent[]
     */
    public $torrents = array();

    /**
     * File name
     *
     * @var string
     */
    public $file = null;
}
