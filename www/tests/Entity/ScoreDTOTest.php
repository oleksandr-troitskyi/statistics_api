<?php

namespace App\Tests\Entity;

use App\Entity\ScoreDTO;
use PHPUnit\Framework\TestCase;

class ScoreDTOTest extends TestCase
{
    public function testIsReturnsCorrectValues()
    {
        $reviewCount = 49;
        $averageScore = 45.45;
        $dateGroup = '2020-03';

        $dto = new ScoreDTO($reviewCount, $averageScore, $dateGroup);

        $this->assertEquals($reviewCount, $dto->getReviewCount());
        $this->assertEquals($averageScore, $dto->getAverageScore());
        $this->assertEquals($dateGroup, $dto->getDateGroup());
    }
}
