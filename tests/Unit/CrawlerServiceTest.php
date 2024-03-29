<?php

namespace Tests\Unit;

use Mockery;
use Tests\TestCase;
use App\Services\CrawlerService;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\BrowserKit\HttpBrowser;

class CrawlerServiceTest extends TestCase
{
    /**
     * A test for get crawler service.
     */
    public function test_get_crawler_return_success(): void
    {
      $crawlerService = $this->getCrawlerService();
      $substrings = ["GBP", "GEL", "HKD"];
      $result = $crawlerService->getCurrencyInfo($substrings);
      
      $this->assertIsArray($result);
      $this->assertCount(3, $result);

      $this->assertContains('GBP', array_column($result, 0));
      $this->assertContains('826', array_column($result, 1));
      $this->assertContains('2', array_column($result, 2));
      $this->assertContains('Libra Esterlina', array_column($result, 3));

      $this->assertContains('GBP', array_column($result, 0));
      $this->assertContains('826', array_column($result, 1));
      $this->assertContains('2', array_column($result, 2));
      $this->assertContains('Libra Esterlina', array_column($result, 3));

      $this->assertContains('GEL', array_column($result, 0));
      $this->assertContains('981', array_column($result, 1));
      $this->assertContains('2', array_column($result, 2));
      $this->assertContains('Lari', array_column($result, 3));

      $this->assertContains('HKD', array_column($result, 0));
      $this->assertContains('344', array_column($result, 1));
      $this->assertContains('2', array_column($result, 2));
      $this->assertContains('Dólar de Hong Kong', array_column($result, 3));
    }

    private function getCrawlerService(): CrawlerService
    {
      $httpBrowser = Mockery::mock(HttpBrowser::class);
      $path = base_path('tests/mocks/wiki.html');
      $htmlContent = file_get_contents($path);
      
      $crawler = new Crawler();
      $crawler->addHtmlContent($htmlContent);
      $httpBrowser->shouldReceive('request')->andReturn($crawler);
      return new CrawlerService($httpBrowser);
    }
}
