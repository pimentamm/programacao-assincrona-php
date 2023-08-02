<?php

include './vendor/autoload.php';

use Psr\Http\Message\ResponseInterface;
use React\EventLoop\Loop;
use React\Http\Browser;
use Symfony\Component\DomCrawler\Crawler;

$loop = Loop::get();
$client = new Browser($loop);

$baseUri = 'https://www.imdb.com';

$client
    ->get($baseUri . '/chart/top/?ref_=nv_mv_250')
    ->then(function(ResponseInterface $response) use ($client, $baseUri) {
        $crawler = new Crawler((string) $response->getBody());
        $nodes = $crawler->filter('ul.ipc-metadata-list a.ipc-title-link-wrapper');
        $links = $nodes->each(function(Crawler $item, $i) use ($baseUri) {
            return [
                'id' => $i,
                'href' => $baseUri . trim($item->attr('href')),
            ];
        });

        $links = array_slice($links, 0, 25);

        foreach ($links as $key => $link) {
            $client
                ->get($link['href'], ['Accept-Language' => 'pt-BR,pt;q=0.9,it;q=0.8'])
                ->then(function(ResponseInterface $response) use ($key) {
                    $crawler = new Crawler((string) $response->getBody());
                    $title = $crawler->filter("h1[data-testid='hero__pageTitle'] > span")->text();
                    $description = $crawler->filter("p[data-testid='plot'] > span")->last()->text();
                    $rate = $crawler->filter("div[data-testid='hero-rating-bar__aggregate-rating__score'] > span")->first()->text();
                
                    echo 'Posição: ' . $key+1 . PHP_EOL;
                    echo 'Título: ' . $title . PHP_EOL;
                    echo 'Descrição: ' . $description . PHP_EOL;
                    echo 'Nota: ' . $rate . "\n\n\n";
                });
        }
    });