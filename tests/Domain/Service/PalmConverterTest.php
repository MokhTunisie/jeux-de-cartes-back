<?php

namespace App\Tests\Domain\Service;

use App\Domain\DTO\Input\CardInputDTO;
use App\Domain\Exception\InvalidCardAttributeException;
use App\Domain\Exception\MissingCardAttributeException;
use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Model\Palm;
use App\Domain\Service\PalmConverter;
use PHPUnit\Framework\TestCase;

class PalmConverterTest extends TestCase
{
    private PalmConverter $palmConverter;

    protected function setUp(): void
    {
        $this->palmConverter = new PalmConverter();
    }

    /**
     * @return array<int, array<string, array<int, CardInputDTO>>>
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
            ['Valid data with 10 cards' => $cards],
        ];
    }

    /**
     * @dataProvider palmDataProvider
     * @throws PalmCardsCountException
     * @throws InvalidCardAttributeException
     * @throws MissingCardAttributeException
     * @param array<int, CardInputDTO> $cards
     */
    public function testConvertToPalmReturnsConvertedPalm(array $cards): void
    {
        $palm = $this->palmConverter->convertToPalm($cards);

        $this->assertInstanceOf(Palm::class, $palm, 'convertToPalm should return a Palm instance.');
        $this->assertCount(count($cards), $palm->cards, 'Converted Palm should contain the same number of cards.');
    }

    public function testConvertToPalmWithEmptyArrayThrowsException(): void
    {
        $this->expectException(PalmCardsCountException::class);

        $cards = [];
        $this->palmConverter->convertToPalm($cards);
    }

    /**
     * @throws PalmCardsCountException
     */
    public function testConvertToPalmWithInvalidCardAttributeThrowsException(): void
    {
        $this->expectException(InvalidCardAttributeException::class);

        $cards = array_fill(0, 9, new CardInputDTO('InvalidColor', 'InvalidValue'));
        $this->palmConverter->convertToPalm($cards);
    }
}