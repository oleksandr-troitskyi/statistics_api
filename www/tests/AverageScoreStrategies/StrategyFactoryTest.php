<?php

namespace App\Tests\AverageScoreStrategies;

use PHPUnit\Framework\TestCase;
use App\Repository\AggregatedScoreRepository;
use App\AverageScoreStrategies\StrategyFactory;
use App\AverageScoreStrategies\NullAverageScoreStrategy;

class StrategyFactoryTest extends TestCase
{
    public function testItReturnsNullAverageScoreStrategy()
    {
        $strategy = new StrategyFactory($this->createMock(AggregatedScoreRepository::class));

        $this->assertInstanceOf(NullAverageScoreStrategy::class, $strategy->create(0));
        $this->assertInstanceOf(NullAverageScoreStrategy::class, $strategy->create(-4));
    }
}
