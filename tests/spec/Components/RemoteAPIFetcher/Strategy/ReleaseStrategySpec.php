<?php

namespace spec\JodaYellowBox\Components\RemoteAPIFetcher\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\ReleaseStrategy;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StrategyInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseStrategySpec extends ObjectBehavior
{
    public function let(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client
    ) {
        $this->beConstructedWith($releaseManager, $releaseRepository, $ticketRepository, $client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ReleaseStrategy::class);
        $this->shouldImplement(StrategyInterface::class);
    }

    public function it_should_be_able_to_fetch_data(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client,
        Project $project,
        Versions $versions
    ) {
        $versions = $this->mockVersions();
        $client->getVersionsInProject(Argument::type(Project::class))->shouldBeCalled()->willReturn($versions);
        //$versions->getAllVersionIds()->shouldBeCalled()->willReturn(['12', '23']);

        $releaseRepository->findByExternalIds(Argument::is(['123', '33']))->shouldBeCalled()->willReturn([]);
        $releaseRepository->add(Argument::type(Release::class))->shouldBeCalledTimes(2);
        $releaseRepository->save()->shouldBeCalled();

        $this->fetchData($project);
    }

    private function mockVersions()
    {
        $versions = new Versions();
        $version = new Version();
        $version->id = '123';
        $version->name = 'Release 1';

        $version2 = new Version();
        $version2->id = '33';
        $version2->name = 'Release 2';

        $versions->add($version);
        $versions->add($version2);

        //$versions->rewind()->shouldBeCalled();
        //$versions->valid()->shouldBeCalled();

        return $versions;
    }
}
