<?php

namespace App\Tests\UI\Controller;

use App\Application\Service\PalmServiceInterface;
use App\Domain\DTO\Input\CardInputDTO;
use App\Domain\Exception\InvalidCardAttributeException;
use App\Domain\Exception\MissingCardAttributeException;
use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Service\PalmConverter;
use App\UI\Controller\PalmController;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class PalmControllerTest extends WebTestCase
{
    private MockObject $palmService;
    private PalmConverter $palmConverter;
    private Serializer $serializer;

    protected function setUp(): void
    {
        $this->palmService = $this->createMock(PalmServiceInterface::class);
        $this->serializer = new Serializer([new ObjectNormalizer()], [new JsonEncoder()]);
        $this->palmConverter = new PalmConverter();
    }

    /**
     * @return array<string, array<int, array<int, CardInputDTO>|int>>
     */
    public function palmDataProvider(): array
    {
        $validCards = [
            new CardInputDTO('Trefle', '7'),
            new CardInputDTO('Coeur', '10'),
            new CardInputDTO('Trefle', '8'),
            new CardInputDTO('Carreaux', '2'),
            new CardInputDTO('Pique', 'Valet'),
            new CardInputDTO('Pique', 'AS'),
            new CardInputDTO('Trefle', 'Dame'),
            new CardInputDTO('Carreaux', 'AS'),
            new CardInputDTO('Coeur', '6'),
            new CardInputDTO('Carreaux', '9')
        ];

        $invalidCards = [
            new CardInputDTO('InvalidSuit', '7'),
            new CardInputDTO('Coeur', 'InvalidRank')
        ];

        return [
            'Valid data with 10 cards' => [$validCards, Response::HTTP_OK],
            'Invalid data with invalid cards' => [$invalidCards, Response::HTTP_BAD_REQUEST],
            'Empty data' => [[], Response::HTTP_BAD_REQUEST],
        ];
    }

    /**
     * @dataProvider palmDataProvider
     * @param array<CardInputDTO> $cards
     * @throws PalmCardsCountException
     * @throws InvalidCardAttributeException
     */
    public function testGetRandomPalmReturnsJsonResponse(array $cards): void
    {
        if (count($cards) < 10) {
            $this->markTestSkipped('This test is only for sets with 10 cards.');
        }


        // Create a real Palm object
        $palm = $this->palmConverter->convertToPalm($cards);

        // Mock the PalmService to return the real Palm object
        $this->palmService->method('generatePalm')->willReturn($palm);
        // @phpstan-ignore argument.type
        $controller = new PalmController($this->palmService, $this->serializer);
        $response = $controller->getRandomPalm();

        $this->assertInstanceOf(JsonResponse::class, $response, 'Expected a JsonResponse instance.');

        $responseData = json_decode($response->getContent() ?: '', true);
        $this->assertArrayHasKey('cards', $responseData, 'Response should contain cards.');
    }

    /**
     * @dataProvider palmDataProvider
     * @param array<CardInputDTO> $cards
     * @throws InvalidCardAttributeException
     * @throws PalmCardsCountException
     * @throws MissingCardAttributeException
     */
    public function testGetSortedPalmReturnsJsonResponse(array $cards): void
    {
        if (count($cards) < 10) {
            $this->markTestSkipped('This test is only for sets with 10 cards.');
        }

        // @phpstan-ignore argument.type
        $controller = new PalmController($this->palmService, $this->serializer);

        // Provide valid JSON with cards
        $requestData = ['cards' => $cards];
        $request = new Request([], [], [], [], [], [], json_encode($requestData) ?: '');

        // Create a real Palm object
        $palm = $this->palmConverter->convertToPalm($cards);

        // Mock the PalmService to return the real Palm object
        $this->palmService->method('convertToPalm')->willReturn($palm);
        $this->palmService->method('sortPalm')->willReturn($palm);

        $response = $controller->getSortedPalm($request);

        $this->assertInstanceOf(JsonResponse::class, $response, 'Expected a JsonResponse instance.');

        $responseData = json_decode($response->getContent() ?: '', true);
        $this->assertArrayHasKey('cards', $responseData, 'Response should contain cards.');
    }

    /**
     * @dataProvider palmDataProvider
     * @param array<CardInputDTO> $cards
     * @throws InvalidCardAttributeException
     * @throws MissingCardAttributeException
     */
    public function testGetSortedPalmThrowsExceptionForLessThanTenCards(array $cards): void
    {
        if (count($cards) > 0) {
            $this->markTestSkipped('This test is only for sets with less than 10 cards.');
        }

        $this->expectException(PalmCardsCountException::class);

        // @phpstan-ignore argument.type
        $controller = new PalmController($this->palmService, $this->serializer);

        // Create a real Palm object
        $palm = $this->palmConverter->convertToPalm($cards);

        // Mock the PalmService to return the real Palm object
        $this->palmService->method('convertToPalm')->willReturn($palm);
        $this->palmService->method('sortPalm')->willReturn($palm);

        // Provide valid JSON with cards
        $requestData = ['cards' => $cards];
        $request = new Request([], [], [], [], [], [], json_encode($requestData) ?: '');

        $controller->getSortedPalm($request);
    }
}