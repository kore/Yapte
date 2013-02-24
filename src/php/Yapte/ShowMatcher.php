<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

/**
 * Base Show Name Matcher
 *
 * @version $Revision$
 */
abstract class ShowMatcher
{
    /**
     * Match show for the given file
     *
     * @param array $shows
     * @param string $fileName
     * @return string
     */
    abstract public function match(array $shows, $string);
}
