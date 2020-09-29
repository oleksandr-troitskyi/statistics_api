<?php

namespace App\Validators;

use App\Repository\HotelRepository;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Collection;

class AverageScoreRequestValidator
{
    private HotelRepository $hotelRepository;
    private array $errors = [];

    public function __construct(HotelRepository $hotelRepository)
    {
        $this->hotelRepository = $hotelRepository;
    }

    public function isValid(array $values): bool
    {
        $validator = Validation::createValidator();
        $constraint = new Collection(
            [
                'hotelId' => [new Required(), new NotNull(), new NotBlank(), new Type('integer'), new Positive()],
                'dateFrom' => [new Required(), new NotNull(), new NotBlank(), new Date()],
                'dateTo' => [new Required(), new NotNull(), new NotBlank(), new Date()],
            ]
        );

        $violations = $validator->validate(
            $values,
            $constraint
        );

        if ($violations->count() > 0) {
            /** @var ConstraintViolation $violation */
            foreach ($violations as $violation) {
                $this->errors[] = $violation->getPropertyPath() . ' - ' . $violation->getMessage();
            }

            return false;
        }

        $hotel = $this->hotelRepository->count(
            ['id' => $values['hotelId']]
        );
        if ($hotel === 0) {
            $this->errors[] = 'no hotel found';
        }

        $dateFrom = new \DateTime($values['dateFrom']);
        $dateTo = new \DateTime($values['dateTo']);

        if ($dateTo < $dateFrom) {
            $this->errors[] = 'date range should be positive';
        }

        if (count($this->errors) > 0) {
            return false;
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
