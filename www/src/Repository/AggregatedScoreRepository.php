<?php

namespace App\Repository;

use App\Entity\DateRangeDTO;
use App\Entity\AggregatedScore;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method AggregatedScore|null find($id, $lockMode = null, $lockVersion = null)
 * @method AggregatedScore|null findOneBy(array $criteria, array $orderBy = null)
 * @method AggregatedScore[]    findAll()
 * @method AggregatedScore[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AggregatedScoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AggregatedScore::class);
    }

    public function getScoresByDateRange(int $hotelId, DateRangeDTO $dateRangeDTO): array
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.hotel', 'h')
            ->andWhere('h.id = :hotelId')
            ->setParameter('hotelId', $hotelId)
            ->andWhere('a.createdDate >= :from')
            ->setParameter('from', $dateRangeDTO->getDateFrom()->format('Y-m-d'))
            ->andWhere('a.createdDate <= :to')
            ->setParameter('to', $dateRangeDTO->getDateTo()->format('Y-m-d'))
            ->orderBy('a.createdDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
