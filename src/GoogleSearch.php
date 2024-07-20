<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;

class GoogleSearch
{
    private $client;
    private $userAgents;
    private $delayBetweenRequests;

    public function __construct()
    {
        $this->client = new Client();
        $this->userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.132 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/67.0.3396.87 Safari/537.36',
            'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0',
        ];
        $this->delayBetweenRequests = 15; // Delay de 15 segundos entre requisições
    }

    public function search($query, $gaps)
    {
        $allLinks = [];

        foreach ($gaps as $gap) {
            $urlquery = "site:" . $query . " " . $gap;
            $url = 'https://www.google.com/search?client=ms-google-coop&q=' . urlencode($urlquery);

            try {
                // Alternar User-Agents para tentar evitar bloqueios
                $userAgent = $this->userAgents[array_rand($this->userAgents)];
                
                // Atraso antes de fazer a requisição
                sleep($this->delayBetweenRequests);

                $response = $this->client->request('GET', $url, [
                    'headers' => [
                        'User-Agent' => $userAgent
                    ]
                ]);

                $html = (string) $response->getBody();

                // Verificar se a página contém um captcha ou resposta 429
                if (strpos($html, 'Our systems have detected unusual traffic') !== false || $response->getStatusCode() == 429) {
                    echo "Google detected unusual traffic. Try again later.\n";
                    break;
                }

                $crawler = new Crawler($html);

                $results = $crawler->filter('div.kCrYT');

                $links = [];
                $validLinkCount = 0;

                $results->each(function (Crawler $node) use (&$links, &$validLinkCount) {
                    $node->filter('a')->each(function (Crawler $linkNode) use (&$links, &$validLinkCount) {
                        $link = $linkNode->attr('href');
                        if ($link) {
                            if (str_contains($link, '/url?esrc=s&q=&rct=j&sa=U&url=')) {
                                $link = str_replace('/url?esrc=s&q=&rct=j&sa=U&url=', '', $link);
                                $link = strtok($link, '&');
                                $links[] = $link;
                                $validLinkCount++;
                            }
                        }
                    });
                });

                $links = array_unique($links);

                if ($validLinkCount > 0) {
                    echo "- Vulnerability found for query: " . $gap . "\n";
                }

                $allLinks = array_merge($allLinks, $links);

            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $statusCode = $e->getResponse()->getStatusCode();
                    if ($statusCode == 429) {
                        echo "429 Too Many Requests. Try again later.\n";
                        break;
                    }
                }
                echo 'Exception caught for query: ' . $gap . ', ', $e->getMessage(), "\n";
            }
        }

        return array_unique($allLinks);
    }
}
