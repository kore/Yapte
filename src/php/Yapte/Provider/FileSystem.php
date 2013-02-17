<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Provider;
use Yapte\NameMatcher;

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
    public function __construct($baseDir, NameMatcher $nameMatcher)
    {
        $this->baseDir = rtrim($baseDir, '/') . '/';
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
            $showNames,
            2,
            true
        );
    }

    /**
     * Get epiosode list
     *
     * @return Episode[]
     */
    public function getEpisodeList(Show $show)
    {
        return array_values(
            array_map(
                function (\SplFileInfo $file) {
                    $episode = $this->nameMatcher->parse($file->getFilename());
                    $episode->internalId = $file->getPath() . '/' . $file->getFilename();

                    return $episode;
                },
                array_filter(
                    iterator_to_array(
                        new \RecursiveIteratorIterator(
                            new \RecursiveDirectoryIterator(
                                $show->internalId,
                                \FilesystemIterator::KEY_AS_PATHNAME |
                                \FilesystemIterator::SKIP_DOTS |
                                \FilesystemIterator::UNIX_PATHS
                            ),
                            \RecursiveIteratorIterator::LEAVES_ONLY
                        )
                    ),
                    'is_file'
                )
            )
        );
    }
}
