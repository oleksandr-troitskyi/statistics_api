<?php

namespace App\Tests\Controller;

use App\Entity\Hotel;
use Doctrine\ORM\EntityManager;
use App\Entity\AggregatedScore;
use App\DataFixtures\HotelFixtures;
use App\DataFixtures\AggreagetedScoreFixtures;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class APIScoreTest extends WebTestCase
{
    private ?EntityManager $entityManager = null;
    private KernelBrowser $client;
    private ?Hotel $hotel = null;

    protected function setUp(): void
    {
        $this->client = self::createClient();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $hotelFixture = new HotelFixtures();
        $hotelFixture->setQty(3)->setQtyReviews(4)->load($this->entityManager);
        $this->hotel = $hotelFixture->getHotels()[0];

        $scoreFixture = new AggreagetedScoreFixtures();
        $scoreFixture->setHotel($this->hotel)->load($this->entityManager);
    }

    public function testItReturns400Code()
    {
        $this->client->request('GET', '/api/score');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testItReturnsErrorsWithNoParametersSent()
    {
        $this->client->request('GET', '/api/score');

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $response);
        $this->assertIsArray($response['errors']);
    }

    public function testItReturnsErrorsWithOneMissedParameterSent()
    {
        $this->client->request('GET', '/api/score', ['dateFrom' => '2019-11-15', 'dateTo' => '2019-12-12']);

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('errors', $response);
        $this->assertIsArray($response['errors']);
    }

    public function testItReturns200Code()
    {
        $dateFrom = (new \DateTime('-1 year -1 month'))->format('Y-m-d');
        $dateTo = (new \DateTime('-1 year'))->format('Y-m-d');

        $this->client->request('GET', '/api/score', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'hotelId' => 1]);

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testItReturnsNoErrorsWithAllParametersSent()
    {
        $score = new AggregatedScore();
        $score->setHotel($this->hotel);
        $score->setReviews(10);
        $score->setScore(4);
        $score->setCreatedDate(new \DateTime('-1 year -5 days'));
        $this->entityManager->persist($score);
        $this->entityManager->flush();

        $dateFrom = (new \DateTime('-1 year -1 month'))->format('Y-m-d');
        $dateTo = (new \DateTime('-1 year'))->format('Y-m-d');

        $this->client->request('GET', '/api/score', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'hotelId' => 1]);

        $response = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertArrayNotHasKey('errors', $response);
    }

    public function testItReturnsScoresWithAllParametersSent()
    {
        $score = new AggregatedScore();
        $score->setHotel($this->hotel);
        $score->setReviews(10);
        $score->setScore(4);
        $score->setCreatedDate(new \DateTime('-1 year -5 days'));
        $this->entityManager->persist($score);
        $this->entityManager->flush();

        $dateFrom = (new \DateTime('-1 year -1 month'))->format('Y-m-d');
        $dateTo = (new \DateTime('-1 year'))->format('Y-m-d');

        $this->client->request('GET', '/api/score', ['dateFrom' => $dateFrom, 'dateTo' => $dateTo, 'hotelId' => 1]);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('scores', $response);
        $this->assertArrayHasKey('review-count', $response['scores'][0]);
        $this->assertArrayHasKey('average-score', $response['scores'][0]);
        $this->assertArrayHasKey('date-group', $response['scores'][0]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
