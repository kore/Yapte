<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte;

use Yapte\Provider\Show;

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
}
