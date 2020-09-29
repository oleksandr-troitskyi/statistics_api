<?php

namespace App\Tests\AverageScoreStrategies;

use App\Entity\ScoreDTO;
use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use PHPUnit\Framework\TestCase;
use App\Entity\AggregatedScore;
use App\Repository\AggregatedScoreRepository;
use App\AverageScoreStrategies\MonthAverageScoreStrategy;

class MonthAverageScoreStrategyTest extends TestCase
{
    public function testItReturnsEmptyCollection()
    {
        $repository = $this->createMock(AggregatedScoreRepository::class);
        $repository->expects($this->exactly(1))
            ->method('getScoresByDateRange');

        $dateRangeDTO = $this->createMock(DateRangeDTO::class);
        $strategy = new MonthAverageScoreStrategy($repository);

        $this->assertEquals(new ScoreDTOCollection(), $strategy->getScores(3, $dateRangeDTO));
    }

    public function testItReturnsFilledCollection()
    {
        $score1 = new AggregatedScore();
        $score2 = new AggregatedScore();
        $score1->setScore(33.5)->setReviews(4)->setCreatedDate(new \DateTime('-1 year -1 month'));
        $score2->setScore(36.5)->setReviews(2)->setCreatedDate(new \DateTime('-1 year -1 month'));
        $repository = $this->createMock(AggregatedScoreRepository::class);
        $repository->expects($this->exactly(1))
            ->method('getScoresByDateRange')->willReturn([$score1, $score2]);

        $dateRangeDTO = $this->createMock(DateRangeDTO::class);
        $dateRangeDTO->method('getDateFrom')->willReturn(new \DateTime('-1 year -1 month'));
        $dateRangeDTO->method('getDateTo')->willReturn(new \DateTime('-1 year'));
        $strategy = new MonthAverageScoreStrategy($repository);

        $result = $strategy->getScores(3, $dateRangeDTO)->getScores();
        $this->assertEquals(1, count($result));
        $this->assertEquals(
            new ScoreDTO(6, 35.0, (new \DateTime('-1 year -1 month'))->format('Y-m')),
            $result[0]
        );
    }
}
