<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\Provider;

use Yapte\Provider;
use Yapte\HttpClient;
use Yapte\NameMatcher;
use Yapte\MetaDataExtractor;

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
     * Name matcher
     *
     * @var NameMatcher
     */
    protected $nameMatcher;

    /**
     * Meta data extractor
     *
     * @var MetaDataExtractor
     */
    protected $metaDataExtractor;

    /**
     * Construct from dependecies
     *
     * @param HttpClient $httpClient
     * @param NameMatcher $nameMatcher
     * @param MetaDataExtractor $metaDataExtractor
     * @return void
     */
    public function __construct(HttpClient $httpClient, NameMatcher $nameMatcher, MetaDataExtractor $metaDataExtractor)
    {
        $this->httpClient = $httpClient;
        $this->nameMatcher = $nameMatcher;
        $this->metaDataExtractor = $metaDataExtractor;
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
        return $this->filterShows(
            $this->getAllShows(),
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
        $html = $this->getDomDocument($show->internalId);
        $xpath = new \DOMXPath($html);

        $episodeIndex = array();
        $episodeBlocks = $xpath->query('//a[@class="epinfo"]/ancestor::tr[@class = "forum_header_border"]');
        for ($i = 0; $i < $episodeBlocks->length; ++$i) {
            $episodeBlock = $episodeBlocks->item($i);
            $title = $xpath->query('.//a[@class="epinfo"]', $episodeBlock)->item(0)->textContent;

            $episode = $this->nameMatcher->parse($title);
            $index = $episode->season . '_' . $episode->episode;
            if (isset($episodeIndex[$index])) {
                $episode = $episodeIndex[$index];
            } else {
                $episodeIndex[$index] = $episode;
            }

            $torrentLinks = $xpath->query('.//a[contains(@class, "download")]', $episodeBlock);
            $torrentMetaData = $this->metaDataExtractor->getMetaData($title);
            for ($j = 0; $j < $torrentLinks->length; ++$j) {
                $episode->torrents[] = new Torrent(
                    array(
                        'url' => $torrentLinks->item($j)->getAttribute('href'),
                        'metaData' => $torrentMetaData,
                    )
                );
            }
        }
        return array_values(array_reverse($episodeIndex));
    }

    /**
     * Get torrents for episode
     *
     * @param Episode $episode
     * @return Torrent[]
     */
    public function getTorrentList(Episode $episode)
    {
        return $episode->torrents;
    }
}
