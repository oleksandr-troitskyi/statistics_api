<?php

namespace App\Command;

use App\Entity\Hotel;
use App\Entity\Review;
use App\Entity\AggregatedScore;
use App\DataFixtures\HotelFixtures;
use App\DataFixtures\ReviewFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FillTablesCommand extends Command
{
    const HOTELS_TO_GENERATE = 10;
    const REVIEWS_TO_GENERATE = 100000;

    protected static $defaultName = 'app:fill-tables';
    protected $entityManager;

    public function __construct(
        string $name = null,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($name);

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this->setDescription('Fills tables with fake data')
            ->setHelp('This command clear tables and then fill them with fake data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->flushTables();
        $this->fillTables();
        $this->fillAggregatedScore();
        $this->showHotels($output);

        return Command::SUCCESS;
    }

    protected function flushTables(): void
    {
        $connection = $this->entityManager->getConnection();
        $platform = $connection->getDatabasePlatform();

        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 0;');
        $connection->executeQuery($platform->getTruncateTableSQL('review', true));
        $connection->executeQuery($platform->getTruncateTableSQL('hotel', true));
        $connection->executeQuery($platform->getTruncateTableSQL('aggregated_score', true));
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS = 1;');
    }

    protected function fillTables(): void
    {
        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(self::HOTELS_TO_GENERATE)->load($this->entityManager);

        $hotels = $hotelFixture->getHotels();

        $reviewFixture = new ReviewFixtures();

        $totalGeneratedReviews = 0;

        foreach ($hotels as $key => $hotel) {
            $hotelReviewsQty = ($key === 9) ? (self::REVIEWS_TO_GENERATE - $totalGeneratedReviews) : rand(9000, 10000);
            $reviewFixture->setQty($hotelReviewsQty)->setHotel($hotel)->load($this->entityManager);
            $totalGeneratedReviews += $hotelReviewsQty;
        }
    }

    protected function fillAggregatedScore(): void
    {
        $hotels = $this->entityManager
            ->getRepository(Hotel::class)
            ->findAll();

        $reviewRepository = $this->entityManager->getRepository(Review::class);

        /** @var Hotel $hotel */
        foreach ($hotels as $hotel) {
            $scoresPerDay = [];

            $reviews = $reviewRepository->getReviewsByHotelId($hotel->getId());
            $scoresPerDay = $this->createScoresPerDay($reviews, $scoresPerDay);

            $this->createAggregatedScoreEntities($scoresPerDay, $hotel);
            $this->entityManager->flush();
        }
    }

    protected function showHotels(OutputInterface $output): void
    {
        $hotels = $this->entityManager
            ->getRepository(Hotel::class)
            ->findAll();

        foreach ($hotels as $hotel) {
            $output->writeln($hotel->getId() . ' ' . $hotel->getName());
        }
        $output->writeln('Total: ' . count($hotels));
    }

    /**
     * @param $reviews
     * @param array $scoresPerDay
     * @return array
     */
    protected function createScoresPerDay($reviews, array $scoresPerDay): array
    {
        /** @var $review $review */
        foreach ($reviews as $review) {
            $scoresPerDay[$review->getCreatedDate()->format('Y-m-d')][] = $review->getScore();
        }

        return $scoresPerDay;
    }

    /**
     * @param array $scoresPerDay
     * @param Hotel $hotel
     * @throws \Exception
     */
    protected function createAggregatedScoreEntities(array $scoresPerDay, Hotel $hotel): void
    {
        foreach ($scoresPerDay as $day => $scores) {
            $aggregatedScore = new AggregatedScore();
            $aggregatedScore->setHotel($hotel);
            $aggregatedScore->setCreatedDate(new \DateTime($day));
            $aggregatedScore->setScore(array_sum($scores) / count($scores));
            $aggregatedScore->setReviews(count($scores));
            $this->entityManager->persist($aggregatedScore);
        }
    }
}
