<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Provider;

/**
 * Provider based on the file system
 *
 * Currently expects folders with show names below the base directory
 *
 * @version $Revision$
 */
class FileSystem extends Provider
{
    /**
     * Base directory
     *
     * @var string
     */
    protected $baseDir;

    /**
     * Construct from dependecies
     *
     * @param string $baseDir
     * @return void
     */
    public function __construct($baseDir)
    {
        $this->baseDir = rtrim($baseDir, '/') . '/';
    }

    /**
     * Get show list
     *
     * @param array $showNames
     * @return Show[]
     */
    public function getShowList(array $showNames)
    {
        return $this->filterShows(
            array_map(
                function ($directory) {
                    return basename($directory);
                },
                array_combine(
                    $values = array_filter(
                        array_map(
                            function ($directory) {
                                return $this->baseDir . $directory . '/';
                            },
                            scandir($this->baseDir)
                        ),
                        'is_dir'
                    ),
                    $values
                )
            ),
            $showNames
        );
    }

    /**
     * Get epiosode list
     *
     * @return Episode[]
     */
    public function getEpisodeList(Show $show)
    {

    }
}
