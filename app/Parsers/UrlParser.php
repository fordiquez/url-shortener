<?php

namespace App\Parsers;

use App\Models\Url;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class UrlParser
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * Create a new instance.
     *
     * @param Client $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get body of given url.
     *
     * @param string $url
     * @return string
     * @throws GuzzleException
     */
    public function getBody(string $url): string
    {
        try {
            $result = $this->client->request('GET', $url);
            $body = $result->getBody();
        } catch (RequestException $exception) {
            $body = '';
        }

        return $body;
    }

    /**
     * Parse the url to collect additional information.
     * @param Url $url
     * @return void
     * @throws GuzzleException
     */
    public function setUrlInfos(Url $url)
    {
        $crawler = new Crawler();
        $crawler->addHtmlContent($this->getBody($url->url));

        $titleNode = $crawler->filter('title');
        $descriptionNode = $crawler->filter('meta[name="description"]');

        $url->title = $titleNode->count() ? $titleNode->first()->text() : null;
        $url->description = $descriptionNode->count() ? $descriptionNode->first()->attr('content') : null;
    }
}
