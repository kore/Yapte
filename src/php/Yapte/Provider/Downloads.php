<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Provider;
use Yapte\NameMatcher;
use Yapte\ShowMatcher;

/**
 * Provider based on the downloads folder
 *
 * @version $Revision$
 */
class Downloads extends Provider
{
    /**
     * Base directory
     *
     * @var string
     */
    protected $baseDir;

    /**
     * Show matcher
     *
     * @var ShowMatcher
     */
    protected $showMatcher;

    /**
     * Name matcher
     *
     * @var NameMatcher
     */
    protected $nameMatcher;

    /**
     * Construct from dependecies
     *
     * @param string $baseDir
     * @return void
     */
    public function __construct($baseDir, ShowMatcher $showMatcher, NameMatcher $nameMatcher)
    {
        $this->baseDir = rtrim($baseDir, '/') . '/';
        $this->showMatcher = $showMatcher;
        $this->nameMatcher = $nameMatcher;
    }

    /**
     * Get show list
     *
     * @param array $showNames
     * @return Show[]
     */
    public function getShowList(array $showNames)
    {
        $shows = array();
        $files = array_filter(
            array_map(
                function ($directory) {
                    return $this->baseDir . $directory;
                },
                scandir($this->baseDir)
            ),
            'is_file'
        );

        foreach ($files as $file) {
            $shows[$this->showMatcher->match($showNames, $file)][] = $file;
        }

        $return = array();
        foreach ($shows as $show => $files) {
            $return[] = new Show(
                array(
                    'name' => $show,
                    'internalId' => $files,
                )
            );
        }
        return $return;
    }

    /**
     * Get epiosode list
     *
     * @return Episode[]
     */
    public function getEpisodeList(Show $show)
    {
        return array_values(
            array_filter(
                array_map(
                    function ($file) {
                        $episode = $this->nameMatcher->parse($file);
                        $episode->internalId = $file;
                        return $episode;
                    },
                    $show->internalId
                )
            )
        );
    }
}
