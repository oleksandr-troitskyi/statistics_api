<?php

namespace App\UseCases;

use App\Entity\DateRangeDTO;
use App\Entity\ScoreDTOCollection;
use App\AverageScoreStrategies\StrategyFactory;

class GetAverageScoreUseCase
{
    private int $hotelId;
    private DateRangeDTO $dateRange;
    private StrategyFactory $strategyFactory;

    public function __construct(
        int $hotelId,
        DateRangeDTO $dateRange,
        StrategyFactory $strategyFactory
    ) {
        $this->hotelId = $hotelId;
        $this->dateRange = $dateRange;
        $this->strategyFactory = $strategyFactory;
    }

    public function execute(): ScoreDTOCollection
    {
        return $this->strategyFactory
            ->create($this->dateRange->getRange())
            ->getScores(
                $this->hotelId,
                $this->dateRange
            );
    }
}
