<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

use Yapte\Provider\Show;
use Yapte\Provider\Episode;

/**
 * Basic HTTP client
 *
 * @version $Revision$
 */
class Controller
{
    /**
     * Normalize show names
     *
     * @param string[] $showNames
     * @param Provider $nameProvider
     * @return string[]
     */
    public function normalizeShowNames(array $showNames, Provider $nameProvider)
    {
        return array_map(
            function (Show $show) {
                return $show->name;
            },
            $nameProvider->getShowList($showNames)
        );
    }

    /**
     * Get missing episodes
     *
     * Compares the local provider with the remote torrent provider
     *
     * @param string[] $shows
     * @param Provider $local
     * @param Provider\Torrents $remote
     * @return Provider\Episode[]
     */
    public function getMissing(array $shows, Provider $local, Provider\Torrents $remote)
    {
        $missing = array();
        $localShows = $this->buildIndex($local, $shows);
        $remoteShows = $this->buildIndex($remote, $shows);

        foreach ($remoteShows as $name => $seasons) {
            foreach ($seasons as $season => $episodes) {
                foreach ($episodes as $episode => $data) {
                    if (!isset($localShows[$name]) ||
                        !isset($localShows[$name][$season]) ||
                        !isset($localShows[$name][$season][$episode])) {
                        $missing[] = $data;
                    }
                }
            }
        }

        return $missing;
    }

    /**
     * Build index of available shows, seasons and episodes
     *
     * @param Provider $provider
     * @param Show[] $shows
     * @return array
     */
    protected function buildIndex(Provider $provider, array $shows)
    {
        $index = array();
        $shows = $provider->getShowList($shows);
        foreach ($shows as $show) {
            $show->episodes = $provider->getEpisodeList($show);

            foreach ($show->episodes as $episode) {
                $episode->internalId = sprintf(
                    "%s %dx%02d",
                    $show->name,
                    $episode->season,
                    $episode->episode
                );
                $index[$show->name][$episode->season][$episode->episode] = $episode;
            }
        }

        return $index;
    }

    /**
     * Downloads missing torrents to target directory
     *
     * @param Provider\Episode[] $missing
     * @param string $target
     * @return void
     */
    public function downloadTorrents(array $missing, $target)
    {
        foreach ($missing as $episode) {
            usort(
                $episode->torrents,
                function ($a, $b) {
                    return $a->metaData->version - $b->metaData->version ?:
                        $a->metaData->quality - $b->metaData->quality;
                }
            );

            foreach ($episode->torrents as $torrent) {
                $targetFile = $target . '/' . $episode->internalId . '.torrent';
                if (@copy($torrent->url, $targetFile)) {
                    if (strpos(file_get_contents($targetFile), ':announce') === false) {
                        unlink($targetFile);
                        continue;
                    }

                    break;
                }
            }
        }
    }

    /**
     * Rename files from source provider
     *
     * Files are moved to the $target directory, unsing the defined pattern as
     * a file / directory name. Strings wrapped in {} are replaced. Currently
     * the following replacements are known:
     *
     * * showTitle
     * * showAbbr
     * * seasonNo
     * * episodeNo
     * * episodeTitle
     *
     * @param array $shows
     * @param Provider\FileSystem $source
     * @param string $target
     * @param string $pattern
     * @return void
     */
    public function fixFileNames(
        array $shows,
        Provider\FileSystem $source,
        Provider $naming,
        $target,
        $pattern = "{showTitle}/Season {seasonNo}/{showAbbr} {seasonNo}x{episodeNo} - {episodeTitle}"
    ) {
        $showData = $this->buildIndex($naming, $shows);
        foreach ($source->getShowList($shows) as $show) {
            $show->episodes = $source->getEpisodeList($show);
            foreach ($show->episodes as $episode) {
                if (!isset($showData[$show->name]) ||
                    !isset($showData[$show->name][$episode->season]) ||
                    !isset($showData[$show->name][$episode->season][$episode->episode])) {
                    continue;
                }

                $fileName = $target . '/' . $this->applyNamePattern(
                    $pattern,
                    $show,
                    $showData[$show->name][$episode->season][$episode->episode]
                ) . '.' . pathinfo($episode->internalId, \PATHINFO_EXTENSION);

                $dirName = dirname($fileName);
                if (!is_dir($dirName)) {
                    mkdir($dirName, 0777, true);
                }

                rename($episode->internalId, $fileName);
            }
        }
    }

    /**
     * Apply name pattern
     *
     * Strings wrapped in {} are replaced. Currently the following replacements
     * are known:
     *
     * * showTitle
     * * showAbbr
     * * seasonNo
     * * episodeNo
     * * episodeTitle
     *
     * @param string $pattern
     * @param Show $show
     * @param Episode $episode
     * @return string
     */
    protected function applyNamePattern($pattern, Show $show, Episode $episode)
    {
        return str_replace(
            array(
                '{showTitle}',
                '{showAbbr}',
                '{seasonNo}',
                '{episodeNo}',
                '{episodeTitle}',
            ),
            array(
                $show->name,
                preg_replace('((\\S)\\S*\\s*)', '\\1', $show->name),
                $episode->season,
                sprintf("%02d", $episode->episode),
                $episode->title,
            ),
            $pattern
        );
    }
}
