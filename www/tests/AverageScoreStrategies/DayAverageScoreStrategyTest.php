<?php

namespace App\Tests\AverageScoreStrategies;

use App\Entity\ScoreDTO;
use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use PHPUnit\Framework\TestCase;
use App\Entity\AggregatedScore;
use App\Repository\AggregatedScoreRepository;
use App\AverageScoreStrategies\DayAverageScoreStrategy;

class DayAverageScoreStrategyTest extends TestCase
{
    public function testItReturnsEmptyCollection()
    {
        $repository = $this->createMock(AggregatedScoreRepository::class);
        $repository->expects($this->exactly(1))
            ->method('getScoresByDateRange');

        $dateRangeDTO = $this->createMock(DateRangeDTO::class);
        $strategy = new DayAverageScoreStrategy($repository);

        $this->assertEquals(new ScoreDTOCollection(), $strategy->getScores(3, $dateRangeDTO));
    }

    public function testItReturnsFilledCollection()
    {
        $score = new AggregatedScore();
        $score->setScore(33.5)->setReviews(4)->setCreatedDate(new \DateTime('-1 year -1 month'));
        $repository = $this->createMock(AggregatedScoreRepository::class);
        $repository->expects($this->exactly(1))
            ->method('getScoresByDateRange')->willReturn([$score]);

        $dateRangeDTO = $this->createMock(DateRangeDTO::class);
        $dateRangeDTO->method('getDateFrom')->willReturn(new \DateTime('-1 year -1 month'));
        $dateRangeDTO->method('getDateTo')->willReturn(new \DateTime('-1 year'));
        $strategy = new DayAverageScoreStrategy($repository);

        $result = $strategy->getScores(3, $dateRangeDTO)->getScores();
        $this->assertEquals(1, count($result));
        $this->assertEquals(new ScoreDTO(4,33.5, (new \DateTime('-1 year -1 month'))->format('Y-m-d')), $result[0]);
    }
}
