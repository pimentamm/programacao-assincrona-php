<?php

include './vendor/autoload.php';

use Symfony\Component\DomCrawler\Crawler;

// 1. visit profile page
$baseUri = 'https://www.imdb.com';
$curl = curl_init($baseUri . '/chart/top/?ref_=nv_mv_250');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);

// 2. get all slides links
$crawler = new Crawler($response);
$nodes = $crawler->filter('ul.ipc-metadata-list a.ipc-title-link-wrapper');
$links = $nodes->each(function(Crawler $item, $i) use ($baseUri) {
    return [
        'id' => $i,
        'href' => $baseUri . trim($item->attr('href')),
    ];
});

$links = array_slice($links, 0, 25);

foreach ($links as $key => $link) {
    // 3. visit slides page
    $curl = curl_init($link['href']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Accept-Language: pt-BR,pt;q=0.9,it;q=0.8']);
    $response = curl_exec($curl);
    curl_close($curl);

    // 4. get all the data
    $crawler = new Crawler($response);
    $title = $crawler->filter("h1[data-testid='hero__pageTitle'] > span")->text();
    $description = $crawler->filter("p[data-testid='plot'] > span")->last()->text();
    $rate = $crawler->filter("div[data-testid='hero-rating-bar__aggregate-rating__score'] > span")->first()->text();

    echo 'Posição: ' . $key+1 . PHP_EOL;
    echo 'Título: ' . $title . PHP_EOL;
    echo 'Descrição: ' . $description . PHP_EOL;
    echo 'Nota: ' . $rate . "\n\n\n";
}