<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

/**
 * Epsiode listing interface
 *
 * @version $Revision$
 */
interface Episodes
{
    /**
     * Get epiosode list
     *
     * @return Episode[]
     */
    public function getEpisodeList(Show $show);
}
