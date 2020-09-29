<?php

namespace App\AverageScoreStrategies;

use App\Repository\AggregatedScoreRepository;

class StrategyFactory
{
    private AggregatedScoreRepository $aggregatedScoreRepository;

    public function __construct(AggregatedScoreRepository $aggregatedScoreRepository)
    {
        $this->aggregatedScoreRepository = $aggregatedScoreRepository;
    }

    public function create(int $days): AverageScoreStrategy
    {
        if ($days >= 1 && $days <= 29) {
            $strategy = new DayAverageScoreStrategy($this->aggregatedScoreRepository);
        }
        if ($days >= 30 && $days <= 89) {
            $strategy = new WeekAverageScoreStrategy($this->aggregatedScoreRepository);
        }
        if ($days >= 90) {
            $strategy = new MonthAverageScoreStrategy($this->aggregatedScoreRepository);
        }

        if (!isset($strategy)) {
            $strategy = new NullAverageScoreStrategy($this->aggregatedScoreRepository);
        }

        return $strategy;
    }
}