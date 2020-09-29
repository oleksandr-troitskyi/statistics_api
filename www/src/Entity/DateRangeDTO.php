<?php

namespace App\Entity;

class DateRangeDTO
{
    private \DateTimeInterface $dateFrom;
    private \DateTimeInterface $dateTo;

    /**
     * DateRangeDTO constructor.
     *
     * @param \DateTimeInterface $dateFrom
     * @param \DateTimeInterface $dateTo
     */
    public function __construct(\DateTimeInterface $dateFrom, \DateTimeInterface $dateTo)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateFrom(): \DateTimeInterface
    {
        return $this->dateFrom;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getDateTo(): \DateTimeInterface
    {
        return $this->dateTo;
    }

    public function getRange(): int
    {
        $interval = $this->dateTo->diff($this->dateFrom);

        return $interval->days ?? 0;
    }
}
