<?php

namespace App\Tests\Entity;

use App\Entity\ScoreDTO;
use PHPUnit\Framework\TestCase;
use App\Entity\ScoreDTOCollection;

class ScoreDTOCollectionTest extends TestCase
{
    public function testItReturnsArrayWithNoItems()
    {
        $collection = new ScoreDTOCollection();

        $this->assertIsArray($collection->getScores());
        $this->assertEquals(0, count($collection->getScores()));
    }

    public function testItReturnsArrayWithTwoItemsWhenUseAddScore()
    {
        $collection = new ScoreDTOCollection();
        $entity = new ScoreDTO(3, 34.33, 'some-text');
        $entity2 = new ScoreDTO(5, 3.33, 'some-another-text');
        $collection->addScore($entity);
        $collection->addScore($entity2);

        $this->assertIsArray($collection->getScores());
        $this->assertEquals(2, count($collection->getScores()));
        $this->assertEquals($entity, $collection->getScores()[0]);
        $this->assertEquals($entity2, $collection->getScores()[1]);
    }

    public function testItReturnsArrayWithTwoItemsWhenUseConstructor()
    {
        $entity = new ScoreDTO(3, 34.33, 'some-text');
        $entity2 = new ScoreDTO(5, 3.33, 'some-another-text');
        $collection = new ScoreDTOCollection([$entity, $entity2]);

        $this->assertIsArray($collection->getScores());
        $this->assertEquals(2, count($collection->getScores()));
        $this->assertEquals($entity, $collection->getScores()[0]);
        $this->assertEquals($entity2, $collection->getScores()[1]);
    }
}
