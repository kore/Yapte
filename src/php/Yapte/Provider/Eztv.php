<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Provider;
use Yapte\HttpClient;

/**
 * Provider based on eztv.it
 *
 * @version $Revision$
 */
class Eztv extends Provider
{
    /**
     * HTTP Client
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * Construct from dependecies
     *
     * @param HttpClient $httpClient
     * @return void
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get show list
     *
     * @return Show[]
     */
    public function getShowList()
    {
        throw new \RuntimeException("@TODO: Implement.");
    }

    /**
     * Get epiosode list
     *
     * @return Episode[]
     */
    public function getEpisodeList(Show $show)
    {
        throw new \RuntimeException("@TODO: Implement.");
    }

    /**
     * Get torrents for episode
     *
     * @param Episode $episode
     * @return Torrent[]
     */
    public function getTorrentList(Episode $episode)
    {
        throw new \RuntimeException("@TODO: Implement.");
    }
}
