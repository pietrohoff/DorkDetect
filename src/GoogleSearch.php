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
        $this->userAgents = $this->loadUserAgentsFromFile('lib/user_agents.txt');
        $this->delayBetweenRequests = rand(5, 15); // Delay de 5 a 15 segundos entre requisições
    }

    private function loadUserAgentsFromFile($filePath)
    {
        $userAgents = [];

        if (file_exists($filePath)) {
            $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                $userAgents[] = trim($line);
            }
        } else {
            throw new \Exception("User agents file not found: " . $filePath);
        }

        return $userAgents;
    }

    public function search($query, $gaps)
    {
        $allLinks = [];

        foreach ($gaps as $gap) {
            $urlquery = "site:" . $query . " " . $gap;
            $url = 'https://www.google.com/search?q=' . urlencode($urlquery);

            $validLinkCount = 0;
            $successfulRequest = false;

            foreach ($this->userAgents as $userAgent) {
                try {
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
                        echo "Google detected unusual traffic or too many requests. Trying another User-Agent...\n";
                        continue;
                    }

                    $crawler = new Crawler($html);

                    $results = $crawler->filter('div.kCrYT');

                    $links = [];

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
                    } else {
                        echo "- No Vulnerability found for query: " . $gap . "\n";
                    }

                    $allLinks = array_merge($allLinks, $links);
                    $successfulRequest = true;
                    break; // Sai do loop de User-Agents se a requisição for bem-sucedida
                } catch (RequestException $e) {
                    if ($e->hasResponse()) {
                        $statusCode = $e->getResponse()->getStatusCode();
                        if ($statusCode == 429) {
                            echo "429 Too Many Requests. Trying another User-Agent...\n";
                            continue;
                        }
                    }
                    echo 'Exception caught for query: ' . $gap . ' with User-Agent: ' . $userAgent . ', ', $e->getMessage(), "\n";
                }
            }

            if (!$successfulRequest) {
                echo "- No successful request for query: " . $gap . " with available User-Agents\n";
            }
        }

        return array_unique($allLinks);
    }
}
