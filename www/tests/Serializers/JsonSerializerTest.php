<?php

namespace App\Tests\Serializers;

use App\Entity\ScoreDTO;
use PHPUnit\Framework\TestCase;
use App\Entity\ScoreDTOCollection;
use App\Serializers\JsonSerializer;

class JsonSerializerTest extends TestCase
{
    public function testItReturnsCorrectJsonForOneEntity()
    {
        $serializer = new JsonSerializer();
        $entity = new ScoreDTO(3, 34.33, 'some-text');

        $this->assertJsonStringEqualsJsonString(
            '{"review-count": 3, "average-score": 34.33, "date-group": "some-text"}',
            $serializer->serialize($entity)
        );
    }

    public function testItReturnsCorrectJsonForCollection()
    {
        $serializer = new JsonSerializer();
        $entity = new ScoreDTO(3, 34.33, 'some-text');
        $collection = new ScoreDTOCollection([$entity]);

        $this->assertJsonStringEqualsJsonString(
            '{"scores": [{"review-count": 3, "average-score": 34.33, "date-group": "some-text"}]}',
            $serializer->serialize($collection)
        );
    }
}
