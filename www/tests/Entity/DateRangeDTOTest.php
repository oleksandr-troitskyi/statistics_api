<?php

namespace App\Tests\Entity;

use App\Entity\DateRangeDTO;
use PHPUnit\Framework\TestCase;

class DateRangeDTOTest extends TestCase
{
    public function testIsReturnsCorrectValues()
    {
        $dateFrom = new \DateTime('-1 year -1 month');
        $dateTo = new \DateTime('-1 year');

        $dto = new DateRangeDTO($dateFrom, $dateTo);

        $this->assertEquals($dateFrom, $dto->getDateFrom());
        $this->assertEquals($dateTo, $dto->getDateTo());
    }

    public function testItCalculatesRange()
    {
        $dateFrom = new \DateTime('-1 year -1 month');
        $dateTo = new \DateTime('-1 year');

        $dto = new DateRangeDTO($dateFrom, $dateTo);

        $this->assertEquals(31, $dto->getRange());
    }
}
