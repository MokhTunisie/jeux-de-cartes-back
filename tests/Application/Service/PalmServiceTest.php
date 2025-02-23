<?php
namespace App\Tests\Application\Service;

use App\Application\Service\PalmService;
use App\Domain\DTO\Input\CardInputDTO;
use App\Domain\Exception\InvalidCardAttributeException;
use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Model\Palm;
use App\Domain\Service\CardShufflerInterface;
use App\Domain\Service\PalmConverter;
use App\Domain\Service\PalmConverterInterface;
use App\Domain\Service\PalmSorter;
use App\Domain\Service\PalmSorterInterface;
use DG\BypassFinals;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PalmServiceTest extends TestCase
{
    private MockObject $cardShuffler;
    private MockObject $palmSorter;
    private MockObject $palmConverterMock;
    private PalmConverter $palmConverter;
    private PalmService $palmService;

    protected function setUp(): void
    {
        BypassFinals::enable();
        $this->cardShuffler = $this->createMock(CardShufflerInterface::class);
        $this->palmSorter = $this->createMock(PalmSorterInterface::class);
        $this->palmConverter = new PalmConverter();
        $this->palmConverterMock = $this->createMock(PalmConverterInterface::class);
        $this->palmService = new PalmService($this->cardShuffler, $this->palmSorter, $this->palmConverter);
    }

    /**
     * @return array<string, array<int, array<int, CardInputDTO>>>
     */
    public function palmDataProvider(): array
    {
        $cards = [
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
        return [
            'Valid data with 10 cards' => [$cards],
        ];
    }

    /**
     * @param array<int, CardInputDTO> $cards
     * @dataProvider palmDataProvider
     * @throws PalmCardsCountException
     */
    public function testGeneratePalmReturnsShuffledPalm(array $cards): void
    {
        $palm = $this->palmConverter->convertToPalm($cards);
        $this->cardShuffler->method('shuffleCards')->willReturn($palm);

        $result = $this->palmService->generatePalm();

        $this->assertInstanceOf(Palm::class, $result, 'generatePalm should return a Palm instance.');
        $this->assertCount(count($cards), $result->cards, 'Shuffled Palm should contain the same number of cards.');
    }

    /**
     * @param array<int, CardInputDTO> $cards
     * @dataProvider palmDataProvider
     * @throws PalmCardsCountException
     * @throws InvalidCardAttributeException
     */
    public function testSortPalmReturnsSortedPalm(array $cards): void
    {
        $palmSorter = new PalmSorter();
        $palmConverter = new PalmConverter();
        $palm = $palmConverter->convertToPalm($cards);
        $this->palmSorter->method('sortPalm')->willReturn($palmSorter->sortPalm($palm));
        $sortedPalm = $this->palmService->sortPalm($palm);

        $this->assertInstanceOf(Palm::class, $sortedPalm, 'sortPalm should return a Palm instance.');
        $this->assertCount(10, $sortedPalm->cards, 'Sorted Palm should also contain 10 cards.');
    }

    /**
     * @param array<int, CardInputDTO> $cards
     * @dataProvider palmDataProvider
     * @throws PalmCardsCountException
     */
    public function testConvertToPalmReturnsConvertedPalm(array $cards): void
    {
        $palm = $this->palmConverter->convertToPalm($cards);
        $this->palmConverterMock->method('convertToPalm')->willReturn($palm);

        $result = $this->palmService->convertToPalm($cards);

        $this->assertInstanceOf(Palm::class, $result, 'convertToPalm should return a Palm instance.');
        $this->assertCount(count($cards), $result->cards, 'Converted Palm should contain the same number of cards.');
    }

    public function testConvertToPalmWithEmptyArrayReturnsEmptyPalm(): void
    {
        $cards = [];
        $this->expectException(PalmCardsCountException::class);

        $palm = $this->palmConverter->convertToPalm($cards);
        $this->palmConverterMock->method('convertToPalm')->willReturn($palm);

        $result = $this->palmService->convertToPalm($cards);

        $this->assertInstanceOf(Palm::class, $result, 'convertToPalm should return a Palm instance.');
        $this->assertCount(0, $result->cards, 'Converted Palm with empty array should contain 0 cards.');
    }
}