<?php

namespace App\Tests\Repositories;

use App\Entity\Hotel;
use Doctrine\ORM\EntityManager;
use App\DataFixtures\HotelFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HotelRepositoryTest extends WebTestCase
{
    private ?EntityManager $entityManager = null;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testItReturnsNoHotels()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);

        $repository = $this->entityManager->getRepository(Hotel::class);

        $this->assertEquals(null, $repository->findById(453));
    }

    public function testItReturnsHotelEntity()
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->load($this->entityManager);
        $hotel = $hotelFixture->getHotels()[1];

        $repository = $this->entityManager->getRepository(Hotel::class);

        $this->assertEquals($hotel, $repository->findById($hotel->getId()));
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
