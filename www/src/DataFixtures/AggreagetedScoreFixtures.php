<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Hotel;
use App\Entity\AggregatedScore;
use Faker\Provider\en_GB\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AggreagetedScoreFixtures extends Fixture
{
    private ObjectManager $manager;
    private Generator $faker;
    private Hotel $hotel;

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
        $score = new AggregatedScore();
        $score->setHotel($this->hotel);
        $score->setScore($this->faker->randomFloat(1, 2, 10));
        $score->setReviews($this->faker->randomNumber(3));
        $score->setCreatedDate($this->faker->dateTimeBetween('-2 years', '-1 second'));
        $manager->persist($score);
        $manager->flush();
    }
}
