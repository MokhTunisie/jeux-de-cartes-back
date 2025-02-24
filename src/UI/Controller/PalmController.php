<?php

namespace App\UI\Controller;

use App\Application\Service\PalmServiceInterface;
use App\Domain\DTO\Input\PalmInputDTO;
use App\Domain\DTO\Output\PalmOutputDTO;
use App\Domain\Exception\MissingCardAttributeException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class PalmController extends AbstractController
{
    /**
     * @param Serializer $serializer
     */
    public function __construct(
        private readonly PalmServiceInterface $palmService,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[OA\Get(
        summary: "Get a random palm",
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns a random palm",
                content: new OA\JsonContent(ref: new Model(type: PalmOutputDTO::class))
            )
        ]
    )]
    #[Route("/api/palm/random", methods: ["GET"])]
    public function getRandomPalm(): JsonResponse
    {
        $palm = $this->palmService->generatePalm();
        $palmOutputDTO = $this->serializer->denormalize($palm, PalmOutputDTO::class);

        return new JsonResponse($palmOutputDTO, Response::HTTP_OK, [], false);
    }

    /**
     * @throws MissingCardAttributeException
     */
    #[OA\Post(
        path: "/api/palm/sorted",
        summary: "Get a sorted palm",
        requestBody: new OA\RequestBody(
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "cards", type: "array", items: new OA\Items(type: "string"))
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Returns a sorted palm",
                content: new OA\JsonContent(ref: new Model(type: PalmOutputDTO::class))
            ),
            new OA\Response(
                response: 400,
                description: "Invalid input"
            )
        ]
    )]
    #[Route("/api/palm/sorted", methods: ["POST"])]
    public function getSortedPalm(Request $request): JsonResponse
    {
        $data = $request->getContent();
        try {
            $palmInputDTO = $this->serializer->deserialize($data, PalmInputDTO::class, 'json');
        } catch (MissingConstructorArgumentsException $e) {
            throw new MissingCardAttributeException();
        }

        $errorMessages = $this->palmService->validateAndProcessPalmInput($palmInputDTO);
        if (count($errorMessages) > 0) {
            return new JsonResponse(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $palm = $this->palmService->convertToPalm($palmInputDTO->cards);
        $sortedPalm = $this->palmService->sortPalm($palm);
        $palmOutputDTO = $this->serializer->denormalize($sortedPalm, PalmOutputDTO::class);

        return new JsonResponse($palmOutputDTO, Response::HTTP_OK, [], false);
    }
}