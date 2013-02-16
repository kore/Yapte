<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

/**
 * TV Show listing interface
 *
 * @version $Revision$
 */
interface Listing
{
    /**
     * Get show list
     *
     * @param array $showNames
     * @return Show[]
     */
    public function getShowList(array $showNames);
}
