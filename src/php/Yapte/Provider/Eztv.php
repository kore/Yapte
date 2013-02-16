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
class Eztv extends Provider implements Provider\Torrents
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

    protected function getDomDocument($url)
    {
        $errorReporting = libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHtml($this->httpClient->request("GET", $url)->body);

        libxml_clear_errors();
        libxml_use_internal_errors($errorReporting);

        return $doc;
    }

    protected function getAllShows()
    {
        $html = $this->getDomDocument("http://eztv.it/showlist/");
        $xpath = new \DOMXPath($html);

        $shows = array();
        $showLinks = $xpath->query('//a[@class="thread_link"]');
        for ($i = 0; $i < $showLinks->length; ++$i) {
            $link = 'http://eztv.it' . $showLinks->item($i)->getAttribute('href');
            $name = $showLinks->item($i)->textContent;

            if (preg_match('(^(.*),\\s+(.*)$)', $name, $matches)) {
                $name = $matches[2] . ' ' . $matches[1];
            }

            $shows[$link] = $name;
        }
        return $shows;
    }

    /**
     * Get show list
     *
     * @param array $showNames
     * @return Show[]
     */
    public function getShowList(array $showNames)
    {
        $allShows = $this->getAllShows();
        $shows = array();
        foreach ($showNames as $showName)
        {
            $showDistances = array_map(
                function ($name) use ($showName) {
                    return levenshtein($name, $showName);
                },
                $allShows
            );

            asort($showDistances, SORT_NUMERIC);

            if (reset($showDistances) > 2) {
                throw new \OutOfRangeException(
                    "Show $showName not found; Closest matches: " . implode(", ", array_slice(array_keys($showDistances), 0, 10))
                );
            }

            $shows[] = new Show(array(
                'name' => $showName,
                'internalId' => key($showDistances),
            ));
        }

        return $shows;
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
