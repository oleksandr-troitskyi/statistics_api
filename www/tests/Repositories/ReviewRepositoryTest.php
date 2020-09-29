<?php

namespace App\Tests\Repositories;

use App\Entity\Review;
use Doctrine\ORM\EntityManager;
use App\DataFixtures\HotelFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReviewRepositoryTest extends WebTestCase
{
    private ?EntityManager $entityManager = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testItReturnsEmptyArrayIfNoHotelExists()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);

        $repository = $this->entityManager->getRepository(Review::class);

        $this->assertEquals([], $repository->getReviewsByHotelId(453));
    }

    public function testItReturnsEmptyArrayIfHotelHasNoReviews()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);
        $hotel = $hotelFixture->getHotels()[1];

        $repository = $this->entityManager->getRepository(Review::class);

        $this->assertEquals([], $repository->getReviewsByHotelId($hotel->getId()));
    }

    public function testItReturnsFilledArrayIfHotelHasReviews()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->setQtyReviews(5)->load($this->entityManager);
        $hotel = $hotelFixture->getHotels()[1];

        $repository = $this->entityManager->getRepository(Review::class);

        $this->assertIsArray($repository->getReviewsByHotelId($hotel->getId()));
        $this->assertEquals(5, count($repository->getReviewsByHotelId($hotel->getId())));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
