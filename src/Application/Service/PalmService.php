<?php

namespace App\Application\Service;

use App\Domain\DTO\Input\PalmInputDTO;
use App\Domain\Model\Palm;
use App\Domain\Service\CardShufflerInterface;
use App\Domain\Service\PalmSorterInterface;
use App\Domain\Service\PalmConverterInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PalmService implements PalmServiceInterface
{
    public function __construct(
        private readonly CardShufflerInterface  $cardShuffler,
        private readonly PalmSorterInterface    $palmSorter,
        private readonly PalmConverterInterface $palmConverter,
        private readonly ValidatorInterface $validator,
    )
    {
    }

    public function generatePalm(): Palm
    {
        return $this->cardShuffler->shuffleCards();
    }

    public function sortPalm(Palm $palm): Palm
    {
        return $this->palmSorter->sortPalm($palm);
    }

    public function convertToPalm(array $cards): Palm
    {
        return $this->palmConverter->convertToPalm($cards);
    }

    public function validateAndProcessPalmInput(PalmInputDTO $palmInputDTO): array
    {
        $errors = $this->validator->validate($palmInputDTO);
        $errorMessages = [];
        if (count($errors) > 0) {
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
        }
        return $errorMessages;
    }
}