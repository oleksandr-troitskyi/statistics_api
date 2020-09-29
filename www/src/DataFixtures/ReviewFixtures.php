<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Hotel;
use App\Entity\Review;
use Faker\Provider\en_GB\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ReviewFixtures extends Fixture
{
    const TOTAL_REVIEWS = 1000;

    private ObjectManager $manager;
    private Generator $faker;
    private int $qty = self::TOTAL_REVIEWS;
    private Hotel $hotel;

    public function setQty(int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function setHotel(Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function load(ObjectManager $manager): void
    {
        if (!is_null($this->hotel)) {
            $this->manager = $manager;
            $this->faker = Factory::create();
            $this->faker->addProvider(Address::class);
            $this->loadData($manager);
        }
    }

    public function loadData(ObjectManager $manager): void
    {
        for ($n = 1; $n <= $this->qty; $n++) {
            $review = new Review();
            $review->setHotel($this->hotel);
            $review->setComment($this->faker->paragraph);
            $review->setScore($this->faker->randomFloat(1, 2, 10));
            $review->setCreatedDate($this->faker->dateTimeBetween('-2 years', '-1 seconds'));
            $manager->persist($review);
        }
        $manager->flush();
    }
}
