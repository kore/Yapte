<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\NameMatcher;

use Yapte\NameMatcher;
use Yapte\Provider\Episode;

/**
 * Regular expression based name matcher
 *
 * @version $Revision$
 */
class RegExp extends NameMatcher
{
    /**
     * Regular expressions to figure out season and episode
     *
     * @var string[]
     */
    protected $episodeRegExps = array(
        '(s(?P<season>\\d+)e(?P<episode>\\d+))i',
        '(\\D(?P<season>\\d+)x(?P<episode>\\d+)\\D)i',
        '(\\D(?P<season>\\d+)\\s*of\\s*(?P<episode>\\d+)\\D)i',
        '(\\D(?P<season>\\d+)(?P<episode>\\d{2})\\D)i',
    );

    /**
     * Try to extract epsiode information from string
     *
     * @param string $string
     * @return Provider\Episode
     */
    public function parse($string)
    {
        $episode = false;
        foreach ($this->episodeRegExps as $regExp) {
            if (preg_match($regExp, $string, $matches)) {
                $episode = new Episode(
                    array(
                        'season' => (int) $matches['season'],
                        'episode' => (int) $matches['episode'],
                    )
                );
                break;
            }
        }

        if (!$episode) {
            throw new \UnexpectedValueException("Could not parse '$string'.");
        }

        return $episode;
    }
}
