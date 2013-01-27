<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

/**
 * Torrent listing interface
 *
 * @version $Revision$
 */
interface Torrents
{
    /**
     * Get torrents for episode
     *
     * @param Episode $episode
     * @return Torrent[]
     */
    public function getTorrentList(Episode $episode);
}
