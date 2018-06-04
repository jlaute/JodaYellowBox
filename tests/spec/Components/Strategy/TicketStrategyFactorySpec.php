<?php

namespace spec\JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\Strategy\ReleaseTicketStrategy;
use JodaYellowBox\Components\Strategy\StateTicketStrategy;
use JodaYellowBox\Components\Strategy\TicketStrategyFactory;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TicketStrategyFactorySpec extends ObjectBehavior
{
    public function let(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client
    ) {
        $this->beConstructedWith($releaseManager, $releaseRepository, $ticketRepository, $client, 'blubb', 'diff');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TicketStrategyFactory::class);
    }

    public function it_returns_release_strategy_when_param_is_true()
    {
        $this->getStrategy(true)->shouldReturnAnInstanceOf(ReleaseTicketStrategy::class);
    }

    public function it_returns_state_strategy_when_param_is_false()
    {
        $this->getStrategy(false)->shouldReturnAnInstanceOf(StateTicketStrategy::class);
    }
}
