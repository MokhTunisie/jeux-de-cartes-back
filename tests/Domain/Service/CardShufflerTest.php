<?php
namespace App\Tests\Domain\Service;

use App\Domain\Exception\PalmCardsCountException;
use App\Domain\Model\Palm;
use App\Domain\Service\CardShuffler;
use PHPUnit\Framework\TestCase;

class CardShufflerTest extends TestCase
{
    private CardShuffler $cardShuffler;

    protected function setUp(): void
    {
        $this->cardShuffler = new CardShuffler();
    }

    /**
     * @throws PalmCardsCountException
     */
    public function testShuffleCardsReturnsPalmInstance(): void
    {
        $result = $this->cardShuffler->shuffleCards();

        $this->assertInstanceOf(Palm::class, $result, 'shuffleCards should return a Palm instance.');
    }

    /**
     * @throws PalmCardsCountException
     */
    public function testShuffleCardsReturnsTenUniqueCards(): void
    {
        $result = $this->cardShuffler->shuffleCards();

        $this->assertCount(10, $result->cards, 'Shuffled Palm should contain 10 cards.');
        $this->assertCount(10, array_unique($result->cards, SORT_REGULAR), 'Shuffled Palm should contain 10 unique cards.');
    }

    public function testShuffleCardsThrowsExceptionForInvalidCardCount(): void
    {
        $this->expectException(PalmCardsCountException::class);

        // Simulate invalid behavior by overriding the shuffleCards method
        $cardShufflerMock = $this->getMockBuilder(CardShuffler::class)
            ->onlyMethods(['shuffleCards'])
            ->getMock();

        $cardShufflerMock->method('shuffleCards')->will($this->returnCallback(function() {
            $cards = [];
            return new Palm($cards);
        }));

        $cardShufflerMock->shuffleCards();
    }
}