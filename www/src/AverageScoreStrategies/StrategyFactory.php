<?php

namespace App\AverageScoreStrategies;

use App\Repository\AggregatedScoreRepository;

class StrategyFactory
{
    const MIN_DAYS_FOR_DAY_AVERAGE_INCLUDING = 1;
    const MAX_DAYS_FOR_DAY_AVERAGE_INCLUDING = 29;
    const MIN_DAYS_FOR_WEEK_AVERAGE_INCLUDING = 30;
    const MAX_DAYS_FOR_WEEK_AVERAGE_INCLUDING = 89;
    const MIN_DAYS_FOR_MONTH_AVERAGE_INCLUDING = 90;

    private AggregatedScoreRepository $aggregatedScoreRepository;

    public function __construct(AggregatedScoreRepository $aggregatedScoreRepository)
    {
        $this->aggregatedScoreRepository = $aggregatedScoreRepository;
    }

    public function create(int $days): AverageScoreStrategy
    {
        if ($days >= self::MIN_DAYS_FOR_DAY_AVERAGE_INCLUDING && $days <= self::MAX_DAYS_FOR_DAY_AVERAGE_INCLUDING) {
            $strategy = new DayAverageScoreStrategy($this->aggregatedScoreRepository);
        }
        if ($days >= self::MIN_DAYS_FOR_WEEK_AVERAGE_INCLUDING && $days <= self::MAX_DAYS_FOR_WEEK_AVERAGE_INCLUDING) {
            $strategy = new WeekAverageScoreStrategy($this->aggregatedScoreRepository);
        }
        if ($days >= self::MIN_DAYS_FOR_MONTH_AVERAGE_INCLUDING) {
            $strategy = new MonthAverageScoreStrategy($this->aggregatedScoreRepository);
        }

        if (!isset($strategy)) {
            $strategy = new NullAverageScoreStrategy($this->aggregatedScoreRepository);
        }

        return $strategy;
    }
}