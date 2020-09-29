<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Hotel;
use Faker\Provider\en_GB\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class HotelFixtures extends Fixture
{
    const TOTAL_HOTELS = 10;
    const TOTAL_REVIEWS = 100000;

    private ObjectManager $manager;
    private Generator $faker;
    private int $qty = self::TOTAL_HOTELS;
    private int $qtyReviews = 0;
    private array $hotels = [];

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getHotels(): array
    {
        return $this->hotels;
    }

    public function setQtyReviews(int $qty): self
    {
        $this->qtyReviews = $qty;

        return $this;
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->faker->addProvider(Address::class);
        $this->loadData($manager);
    }

    public function loadData(ObjectManager $manager): void
    {
        for ($i = 1; $i <= $this->qty; $i++) {
            $hotel = new Hotel();
            $hotel->setName($this->faker->company);
            $manager->persist($hotel);
            $manager->flush();

            if ($this->qtyReviews > 0) {
                $reviewFixture = new ReviewFixtures();
                $reviewFixture->setHotel($hotel)->setQty($this->qtyReviews)->load($this->manager);
            }

            $this->hotels[] = $hotel;
        }
    }
}
