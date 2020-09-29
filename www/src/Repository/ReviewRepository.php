<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Review|null find($id, $lockMode = null, $lockVersion = null)
 * @method Review|null findOneBy(array $criteria, array $orderBy = null)
 * @method Review[]    findAll()
 * @method Review[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function getReviewsByHotelId(int $hotelId): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.hotel', 'h')
            ->where('h.id = :hotelId')
            ->setParameter('hotelId', $hotelId)
            ->orderBy('r.createdDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
