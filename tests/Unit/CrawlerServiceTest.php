<?php

namespace Tests\Unit;

use App\Services\CrawlerService;
use Tests\TestCase;

class CrawlerServiceTest extends TestCase
{
    /**
     * A test for get crawler service.
     */
    public function test_get_crawler_return_success(): void
    {
        $serviceCrawler = new CrawlerService();
        $substrings = ["GBP", "GEL", "HKD"];
        $result = $serviceCrawler->get($substrings);

        $this->assertIsArray($result);
        $this->assertCount(3, $result);

        $this->assertContains(['GBP', '826', '2', 'Libra Esterlina',
        [
            0 => "\u{A0}Reino Unido, Ilha de Man, Guernesey, Jersey",
            1 => [
              0 => "upload.wikimedia.org/wikipedia/commons/thumb/8/83/Flag_of_the_United_Kingdom_%283-5%29.svg/22px-Flag_of_the_United_Kingdom_%283-5%29.svg.png"
            ]
          ]
        ], $result);
        $this->assertContains(['GEL', '981', '2', 'Lari', 
        [
            0 => "\u{A0}Geórgia",
            1 => [
              0 => "upload.wikimedia.org/wikipedia/commons/thumb/0/0f/Flag_of_Georgia.svg/22px-Flag_of_Georgia.svg.png"
            ]
          ]
        ], $result);
        $this->assertContains(['HKD', '344', '2', 'Dólar de Hong Kong', 
        [
            0 => "\u{A0}Hong Kong",
            1 => [
              0 => "upload.wikimedia.org/wikipedia/commons/thumb/5/5b/Flag_of_Hong_Kong.svg/22px-Flag_of_Hong_Kong.svg.png"
            ]
          ]
        ], $result);
    }
}
