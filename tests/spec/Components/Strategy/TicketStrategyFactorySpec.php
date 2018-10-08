<?php

namespace spec\JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\Config\PluginConfig;
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
        ClientInterface $client,
        PluginConfig $pluginConfig
    ) {
        $pluginConfig->get('JodaYellowBoxExternalStatusId')->shouldBeCalled();
        $pluginConfig->get('JodaYellowBoxReleaseToDisplay')->shouldBeCalled();

        $this->beConstructedWith($releaseManager, $releaseRepository, $ticketRepository, $client, $pluginConfig);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TicketStrategyFactory::class);
    }

    public function it_returns_release_strategy_when_param_is_true(PluginConfig $pluginConfig)
    {
        $pluginConfig->get('JodaYellowBoxTicketsDependOnRelease')->shouldBeCalled()->willReturn(true);
        $this->getStrategy(true)->shouldReturnAnInstanceOf(ReleaseTicketStrategy::class);
    }

    public function it_returns_state_strategy_when_param_is_false(PluginConfig $pluginConfig)
    {
        $pluginConfig->get('JodaYellowBoxTicketsDependOnRelease')->shouldBeCalled()->willReturn(false);
        $this->getStrategy(false)->shouldReturnAnInstanceOf(StateTicketStrategy::class);
    }
}
