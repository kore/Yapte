<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

use Yapte\Provider\Show;

/**
 * Provider base class
 *
 * @version $Revision$
 */
abstract class Provider implements Provider\Listing, Provider\Episodes
{
    /**
     * Filter shows.
     *
     * Shows must be provided as an array(id => name). The $filter array must
     * be a name of shows to filter for.
     *
     * The distance is the maximum allowed distance measured using levenshtein.
     *
     * @param array $allShows
     * @param array $filter
     * @param int $maxDistance
     * @return Show[]
     */
    protected function filterShows(array $allShows, array $filter, $maxDistance = 2)
    {
        $shows = array();
        foreach ($filter as $showName) {
            $showDistances = array_map(
                function ($name) use ($showName) {
                    return levenshtein($name, $showName);
                },
                $allShows
            );

            asort($showDistances, SORT_NUMERIC);

            if (reset($showDistances) > $maxDistance) {
                throw new \OutOfRangeException(
                    "Show $showName not found; Closest matches: " .
                    implode(", ", array_slice(array_keys($showDistances), 0, 10))
                );
            }

            $shows[] = new Show(
                array(
                    'name' => $showName,
                    'internalId' => key($showDistances),
                )
            );
        }

        return $shows;
    }
}
