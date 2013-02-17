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
     * @return Episode[]
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
                $index[$show->name][$episode->season][$episode->episode] = $episode;
            }
        }

        return $index;
    }
}
