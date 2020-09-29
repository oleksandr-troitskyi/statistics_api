<?php

namespace App\Entity;

class ScoreDTOCollection
{
    private array $scores = [];

    public function __construct(array $scores = [])
    {
        foreach ($scores as $score) {
            if ($score instanceof ScoreDTO) {
                $this->addScore($score);
            }
        }
    }

    public function addScore(ScoreDTO $scoreDTO): void
    {
        $this->scores[] = $scoreDTO;
    }

    public function getScores(): array
    {
        return $this->scores;
    }
}
