<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\ShowMatcher;

use Yapte\ShowMatcher;

/**
 * Base Show Name Matcher
 *
 * @version $Revision$
 */
class RegExp extends ShowMatcher
{
    /**
     * Cached PCRE matches
     *
     * @var string[]
     */
    protected $matches;

    /**
     * Match show for the given file
     *
     * @param array $shows
     * @param string $fileName
     * @return string
     */
    public function match(array $shows, $fileName)
    {
        foreach ($this->buildMatchArray($shows) as $regExp => $show) {
            if (preg_match($regExp, $fileName)) {
                return $show;
            }
        }

        throw new \UnexpectedValueException("Could not guess show for $fileName");
    }

    /**
     * Build array with PCRE matches to match show names in file names
     *
     * @param array $shows
     * @return string[]
     */
    protected function buildMatchArray(array $shows)
    {
        if ($this->matches) {
            return $this->matches;
        }

        foreach ($shows as $show) {
            $this->matches['(' . preg_replace('(\\s+)i', '[^a-z0-9]+', $show) . ')i'] = $show;
        }

        return $this->matches;
    }
}
