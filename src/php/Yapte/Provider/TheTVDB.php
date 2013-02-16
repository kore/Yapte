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
class TheTVDB extends Provider
{
    /**
     * HTTP Client
     *
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * TheTVDB API key
     *
     * See: http://thetvdb.com/?tab=apiregister
     *
     * @var string
     */
    protected $apiKey;

    /**
     * Language
     *
     * @var string
     */
    protected $language;

    /**
     * Mirro to use
     *
     * @var string
     */
    protected $mirror;

    /**
     * Construct from dependecies
     *
     * @param HttpClient $httpClient
     * @return void
     */
    public function __construct(HttpClient $httpClient, $apiKey, $language = 'en')
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
        $this->language = $language;
    }

    /**
     * Get show list
     *
     * @param array $showNames
     * @return Show[]
     */
    public function getShowList(array $showNames)
    {
        return array_map(
            function ($show) {
                $showInfo = new \DOMDocument();
                $showInfo->loadXml($this->httpClient->request(
                    "GET",
                    $url = "http://www.thetvdb.com/api/GetSeries.php?seriesname=" . urlencode($show)
                )->body);

                $showElement = $this->queryXPath(
                    $showInfo,
                    '//SeriesName[php:functionString("strtolower", text()) = "' . strtolower($show) . '"]'
                );

                if (!$showElement) {
                    throw new \OutOfRangeException(
                        "Show '$show' not found - possible names: " .
                        implode(
                            ", ",
                            array_map(
                                function ($showName) {
                                    return $showName->textContent;
                                },
                                $this->queryXPath($showInfo, '//SeriesName')
                            )
                        )
                    );
                }

                return new Show(array(
                    'internalId' => $this->queryXPath($showInfo, './parent::*/id', $showElement[0])[0]->textContent,
                    'name' => $showElement[0]->textContent
                ));
            },
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

    /**
     * Get mirror to use
     *
     * @return string
     */
    protected function getMirror()
    {
        if ($this->mirror) {
            return $this->mirror;
        }

        $mirrors = new \DOMDocument();
        $mirrors->loadXml($this->httpClient->request(
            "GET", "http://www.thetvdb.com/api/" . $this->apiKey . "/mirrors.xml"
        )->body);

        $mirrorsXPath = new \DOMXPath($mirrors);
        $mirrors = $mirrorsXPath->query('//Mirror/typemask[text() = "1" or text() = "3" or text() = "7"]/parent::*/mirrorpath');
        $this->mirror = $mirrors->item(mt_rand(0, $mirrors->length - 1))->textContent;
        return $this->mirror;
    }

    /**
     * Query XPath and return reults as an array
     *
     * @param \DOMDocument $document
     * @param string $query
     * @param \DOMNode $context
     * @return array
     */
    protected function queryXPath(\DOMDocument $document, $query, \DOMNode $context = null)
    {
        $xPath = new \DOMXPath($document);
        $xPath->registerNamespace("php", "http://php.net/xpath");
        $xPath->registerPHPFunctions('strtolower');

        $resultArray = array();
        $result = $xPath->query($query, $context);
        for ($i = 0; $i < $result->length; ++$i) {
            $resultArray[] = $result->item($i);
        }

        return $resultArray;
    }
}
