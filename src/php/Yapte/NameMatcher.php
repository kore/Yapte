<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

/**
 * Base Name Matcher
 *
 * @version $Revision$
 */
abstract class NameMatcher
{
    /**
     * Try to extract epsiode information from string
     *
     * @param string $string
     * @return Provider\Episode
     */
    abstract public function parse($string);
}
