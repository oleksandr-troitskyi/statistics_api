<?php

namespace App\Tests\AverageScoreStrategies;

use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use PHPUnit\Framework\TestCase;
use App\Repository\AggregatedScoreRepository;
use App\AverageScoreStrategies\NullAverageScoreStrategy;

class NullAverageScoreStrategyTest extends TestCase
{
    public function testItReturnsEmptyCollection()
    {
        $repository = $this->createMock(AggregatedScoreRepository::class);
        $repository->expects($this->exactly(0))
            ->method('getScoresByDateRange');

        $dateRangeDTO = $this->createMock(DateRangeDTO::class);
        $strategy = new NullAverageScoreStrategy($repository);

        $this->assertEquals(new ScoreDTOCollection(), $strategy->getScores(3, $dateRangeDTO));
    }
}
