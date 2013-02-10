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
class Caching extends HttpClient
{
    /**
     * Inner HTTP client
     *
     * @var HttpClient
     */
    protected $innerClient;

    /**
     * Cache directory
     *
     * @var string
     */
    protected $cacheDir;

    /**
     * Time to live in seconds
     *
     * @var int
     */
    protected $time;

    /**
     * Construct from cachedir an time to live
     *
     * @param string $cacheDir
     * @param int $time
     * @return void
     */
    public function __construct(HttpClient $innerClient, $cacheDir, $time)
    {
        $this->innerClient = $innerClient;
        $this->cacheDir = $cacheDir;
        $this->time = $time;
    }

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
    public function request($method, $path, Message $message = null)
    {
        if ($method !== 'GET') {
            return $this->innerClient->request($method, $path, $message);
        }

        $fileName = preg_replace('([^A-Za-z0-9]+)', '_', $path);
        $filePath = $this->cacheDir . '/' . $fileName;

        if (file_exists($filePath) &&
            filemtime($filePath) > (time() - $this->time)) {
            return unserialize(file_get_contents($filePath));
        }

        $response = $this->innerClient->request($method, $path, $message);

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }

        file_put_contents($filePath, serialize($response));
        return $response;
    }
}
