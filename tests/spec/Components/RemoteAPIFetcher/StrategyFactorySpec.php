<?php

namespace spec\JodaYellowBox\Components\RemoteAPIFetcher;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\ReleaseStrategy;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StateStrategy;
use JodaYellowBox\Components\RemoteAPIFetcher\StrategyFactory;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StrategyFactorySpec extends ObjectBehavior
{
    public function let(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client
    ) {
        $this->beConstructedWith($releaseManager, $releaseRepository, $ticketRepository, $client, 'blubb');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StrategyFactory::class);
    }

    public function it_returns_release_strategy_when_param_is_true()
    {
        $this->getStrategy(true)->shouldReturnAnInstanceOf(ReleaseStrategy::class);
    }

    public function it_returns_state_strategy_when_param_is_false()
    {
        $this->getStrategy(false)->shouldReturnAnInstanceOf(StateStrategy::class);
    }
}
