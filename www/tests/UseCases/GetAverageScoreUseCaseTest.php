<?php

namespace App\Tests\UseCases;

use App\Entity\DateRangeDTO;
use PHPUnit\Framework\TestCase;
use App\Entity\ScoreDTOCollection;
use App\UseCases\GetAverageScoreUseCase;
use App\AverageScoreStrategies\StrategyFactory;
use App\AverageScoreStrategies\AverageScoreStrategy;

class GetAverageScoreUseCaseTest extends TestCase
{
    public function testItUsesStrategyAndReturnScoreCollection()
    {
        $dateRange = $this->createMock(DateRangeDTO::class);
        $dateRange->method('getRange')->willReturn(0);
        $strategy = $this->createMock(AverageScoreStrategy::class);
        $strategy->expects($this->once())->method('getScores')->willReturn(new ScoreDTOCollection());
        $factory = $this->createMock(StrategyFactory::class);
        $factory->method('create')->willReturn($strategy);
        $useCase = new GetAverageScoreUseCase(4, $dateRange, $factory);

        $this->assertInstanceOf(ScoreDTOCollection::class, $useCase->execute());
    }
}
