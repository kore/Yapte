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

    protected function getDomDocument($url)
    {
        $errorReporting = libxml_use_internal_errors(true);

        $doc = new \DOMDocument();
        $doc->loadHtml($this->httpClient->request("GET", $url)->body);

        libxml_clear_errors();
        libxml_use_internal_errors($errorReporting);

        return $doc;
    }

    /**
     * Get show list
     *
     * @param array $showNames
     * @return Show[]
     */
    public function getShowList(array $showNames)
    {
        $html = $this->getDomDocument("http://eztv.it/showlist/");
        $xpath = new \DOMXPath($html);

        $shows = array();
        $showLinks = $xpath->query('//a[@class="thread_link"]');
        for ($i = 0; $i < $showLinks->length; ++$i) {
            $showLink = $showLinks->item($i);
            $shows[] = new Show(
                array(
                    'name' => $showLink->textContent,
                    'internalId' => 'http://eztv.it' . $showLink->getAttribute('href'),
                )
            );
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
