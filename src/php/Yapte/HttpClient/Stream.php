<?php
/**
 * This file is part of Yapte
 *
 * @version $Revision$
 */

namespace Yapte\HttpClient;

use Yapte\HttpClient;

/**
 * Basic HTTP client
 *
 * @version $Revision$
 */
class Stream extends HttpClient
{
    /**
     * Default URL values
     *
     * @var array
     */
    protected $defaults = array(
        'scheme'   => 'http',
        'host'     => null,
        'port'     => null,
        'user'     => null,
        'pass'     => null,
        'query'    => null,
        'fragment' => null,
    );

    /**
     * Execute a HTTP request to the remote server
     *
     * Returns the result from the remote server.
     *
     * @param string $method
     * @param string $path
     * @param Message $message
     * @return Message
     */
    public function request($method, $url, Message $message = null)
    {
        $message = $message ?: new Message();
        $url = $this->processUrl($url, $message);

        $contextOptions = array(
            'http' => array(
                'method'        => $method,
                'content'       => $message->body,
                'ignore_errors' => true,
                'header'        => $this->getRequestHeaders($message->headers),
            ),
        );

        $httpFilePointer = fopen(
            $url,
            'r',
            false,
            stream_context_create($contextOptions)
        );

        // Check if connection has been established successfully
        if ($httpFilePointer === false) {
            throw new CommunicationException($url, $method);
        }

        // Read request body
        $body = '';
        while (!feof($httpFilePointer)) {
            $body .= fgets($httpFilePointer);
        }

        $headers = $this->parseHeaders(stream_get_meta_data($httpFilePointer));
        // This depends on PHP compiled with or without --curl-enable-streamwrappers

        return new Message($headers, $body);
    }

    /**
     * Set defaults
     *
     * Set default value for scheme, host, port, user or pass.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setDefault($key, $value)
    {
        if (!array_key_exists($key, $this->defaults)) {
            throw new \OutOfBoundsException("$key is an invalid property.");
        }

        $this->defaults[$key] = $value;
    }

    /**
     * Parse raw headers
     *
     * @param array $metaData
     * @return array
     */
    protected function parseHeaders(array $metaData)
    {
        $rawHeaders = isset($metaData['wrapper_data']['headers']) ?
            $metaData['wrapper_data']['headers'] :
            $metaData['wrapper_data'];

        $headers = array();
        foreach ($rawHeaders as $lineContent) {
            if (preg_match('(^HTTP/(?P<version>\d+\.\d+)\s+(?P<status>\d+))S', $lineContent, $match)) {
                $headers['version'] = $match['version'];
                $headers['status']  = (int)$match['status'];
            } else {
                list($key, $value) = explode(':', $lineContent, 2);
                $headers[strtolower($key)] = ltrim($value);
            }
        }

        return $headers;
    }

    /**
     * Extract header information from URL and normaliz URL
     *
     * @param string $url
     * @param Message $message
     * @return string
     */
    protected function processUrl($url, Message $message)
    {
        $urlInfo = parse_url($url);
        $urlInfo += $this->defaults;

        if ($urlInfo['user'] || $urlInfo['pass']) {
            $message->headers['Authorization'] = 'Basic ' . base64_encode("{$urlInfo['user']}:{$urlInfo['pass']}");
        }

        $url = $urlInfo['scheme'] . '://' . $urlInfo['host'];
        if ($urlInfo['port']) {
            $this->server .= ':' . $urlInfo['port'];
        }
        $url .= $urlInfo['path'];
        $url .= $urlInfo['query'] ? '?' . $urlInfo['query'] : '';
        $url .= $urlInfo['fragment'] ? '#' . $urlInfo['fragment'] : '';
        return $url;
    }

    /**
     * Get formatted request headers
     *
     * Merged with the default values.
     *
     * @param array $headers
     * @return string
     */
    protected function getRequestHeaders(array $headers)
    {
        $requestHeaders = '';

        foreach ($headers as $name => $value) {
            if (!isset($headers[$name])) {
                $requestHeaders .= "$name: $value\r\n";
            }
        }

        foreach ($headers as $name => $value) {
            $requestHeaders .= "$name: $value\r\n";
        }

        return $requestHeaders;
    }
}
