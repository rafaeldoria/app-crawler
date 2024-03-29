<?php

namespace App\Services;

use Symfony\Component\BrowserKit\HttpBrowser;

class CrawlerService
{
    protected $client;

    public function __construct(HttpBrowser $client)
    {
        $this->client = $client;
    }

    public function getCurrencyInfo($substrings)
    {
        $crawler = $this->getHtmlData();

        $table = $this->getWikiTable($crawler);

        $rows = $table->filter('tr')->each(function ($row) use ($substrings){
            $check = substr($row->text(), 0, 7);
            $search = explode(' ', $check);
            $intersection = array_intersect($search, $substrings);

            if (!empty($intersection)) {
                $cells = $row->filter('td')->each(function ($cell, $i) {
                    if ($i == 4) {
                        $text = $cell->text();
                        $img = $cell->filter('img')->each(function ($img) {
                            $src = $img->attr('src');
                            if (strpos($src, '//') === 0) {
                                $src = substr($src, 2);
                            }
                            return $src;
                        });
                        return [
                            $text,$img,
                        ];
                    } else {
                        return $cell->text();
                    }
                });
                return $cells;
            }
        });
        return array_filter($rows);
    }

    private function getWikiTable($crawler)
    {
        return $crawler->filter('table.wikitable.sortable')->first();
    }

    public function getHtmlData()
    {
        $url = config('crawler.url');
        return $this->client->request('GET', $url);
    }
}