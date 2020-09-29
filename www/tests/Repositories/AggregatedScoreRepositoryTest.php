<?php

namespace App\Tests\Repositories;

use App\Entity\DateRangeDTO;
use Doctrine\ORM\EntityManager;
use App\Entity\AggregatedScore;
use App\DataFixtures\HotelFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AggregatedScoreRepositoryTest extends WebTestCase
{
    private ?EntityManager $entityManager = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testItReturnsEmptyArrayIfNoScoresExists()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);

        $repository = $this->entityManager->getRepository(AggregatedScore::class);

        $this->assertEquals(
            [],
            $repository->getScoresByDateRange(
                453,
                new DateRangeDTO(new \DateTime('-1 year -1 month'), new \DateTime('-1 year'))
            )
        );
    }

    public function testItReturnsEmptyArrayIfHoScoresOnThisRange()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);
        $hotel = $hotelFixture->getHotels()[1];

        $score = new AggregatedScore();
        $score->setHotel($hotel);
        $score->setReviews(10);
        $score->setScore(4);
        $score->setCreatedDate(new \DateTime('-1 year -2 months'));
        $this->entityManager->persist($score);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(AggregatedScore::class);

        $this->assertEquals(
            [],
            $repository->getScoresByDateRange(
                $hotel->getId(),
                new DateRangeDTO(new \DateTime('-1 year -1 month'), new \DateTime('-1 year'))
            )
        );
    }

    public function testItReturnsFilledArrayIfHotelHasScoreRecord()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);
        $hotel = $hotelFixture->getHotels()[1];

        $score = new AggregatedScore();
        $score->setHotel($hotel);
        $score->setReviews(10);
        $score->setScore(4);
        $score->setCreatedDate(new \DateTime('-1 year -5 days'));
        $this->entityManager->persist($score);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(AggregatedScore::class);

        $dateRange = new DateRangeDTO(new \DateTime('-1 year -1 month'), new \DateTime('-1 year'));
        $this->assertIsArray($repository->getScoresByDateRange($hotel->getId(), $dateRange));
        $this->assertEquals(1, count($repository->getScoresByDateRange($hotel->getId(), $dateRange)));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
