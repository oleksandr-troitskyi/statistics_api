<?php

namespace App\Controller;

use App\Entity\DateRangeDTO;
use App\Serializers\JsonSerializer;
use App\UseCases\GetAverageScoreUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\AverageScoreStrategies\StrategyFactory;
use App\Validators\AverageScoreRequestValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class APIController extends AbstractController
{
    private AverageScoreRequestValidator $validator;
    private JsonSerializer $serializer;
    private StrategyFactory $strategyFactory;

    public function __construct(
        AverageScoreRequestValidator $validator,
        JsonSerializer $serializer,
        StrategyFactory $strategyFactory
    ) {
        $this->validator = $validator;
        $this->serializer = $serializer;
        $this->strategyFactory = $strategyFactory;
    }

    public function score(Request $request): Response
    {
        $values = [
            'hotelId' => !is_null($request->get('hotelId') && $request->get('hotelId') !== '') ? (int)$request->get(
                'hotelId'
            ) : $request->get('hotelId'),
            'dateFrom' => $request->get('dateFrom'),
            'dateTo' => $request->get('dateTo'),
        ];

        if ($this->validator->isValid($values) === false) {
            return new Response(\json_encode(['errors' => $this->validator->getErrors()]), Response::HTTP_BAD_REQUEST);
        }

        $dateRange = new DateRangeDTO(new \DateTime($values['dateFrom']), new \DateTime($values['dateTo']));

        $useCase = new GetAverageScoreUseCase(
            $values['hotelId'],
            $dateRange,
            $this->strategyFactory
        );

        return new Response($this->serializer->serialize($useCase->execute()), Response::HTTP_OK);
    }
}
