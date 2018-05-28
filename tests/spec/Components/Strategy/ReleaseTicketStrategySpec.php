<?php

namespace spec\JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;
use JodaYellowBox\Components\Strategy\ReleaseTicketStrategy;
use JodaYellowBox\Components\Strategy\TicketStrategyInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseTicketStrategySpec extends ObjectBehavior
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
        $this->shouldHaveType(ReleaseTicketStrategy::class);
        $this->shouldImplement(TicketStrategyInterface::class);
    }

    public function it_should_be_able_to_fetch_data(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client,
        Project $project,
        Release $release
    ) {
        $this->mockTicketFetching($releaseManager, $ticketRepository, $releaseRepository, $client, $release);

        $this->fetchData($project);
    }

    public function it_change_ticket_data_on_behalf(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client,
        Project $project,
        Release $release
    ) {
        $this->mockReleaseFetching($releaseRepository, $client);

        $releaseManager->getCurrentRelease()->shouldBeCalled()->willReturn($release);
        $release->getExternalId()->shouldBeCalled()->willReturn('33');
        $client->getIssuesByVersion(Argument::type(Version::class))->shouldBeCalled()->willReturn($this->mockIssues());

        $ticketRepository->findByExternalIds(Argument::type('array'))->shouldBeCalled()->willReturn($this->mockExistingTickets());
        $ticketRepository->add(Argument::type(Ticket::class))->shouldBeCalledTimes(2);
        $ticketRepository->save()->shouldBeCalled();

        $this->fetchData($project);
    }

    private function mockReleaseFetching(ReleaseRepository $releaseRepository, ClientInterface $client)
    {
        $versions = $this->mockVersions();
        $client->getVersionsInProject(Argument::type(Project::class))->shouldBeCalled()->willReturn($versions);

        $releaseRepository->findByExternalIds(Argument::is(['123', '33']))->shouldBeCalled()->willReturn([]);
        $releaseRepository->add(Argument::type(Release::class))->shouldBeCalledTimes(2);
        $releaseRepository->save()->shouldBeCalled();
    }

    private function mockExistingTickets()
    {
        $existingTickets = [];
        $ticket1 = new Ticket('Ticket1', '124', null, '1234');
        $ticket2 = new Ticket('Ticket1', '124', null, '111');

        $existingTickets[] = $ticket1;
        $existingTickets[] = $ticket2;

        return $existingTickets;
    }

    private function mockTicketFetching(
        ReleaseManagerInterface $releaseManager,
        TicketRepository $ticketRepository,
        ReleaseRepository $releaseRepository,
        ClientInterface $client,
        Release $release
    ) {
        $this->mockReleaseFetching($releaseRepository, $client);

        $releaseManager->getCurrentRelease()->shouldBeCalled()->willReturn($release);
        $release->getExternalId()->shouldBeCalled()->willReturn('33');
        $client->getIssuesByVersion(Argument::type(Version::class))->shouldBeCalled()->willReturn($this->mockIssues());

        $ticketRepository->findByExternalIds(Argument::type('array'))->shouldBeCalled()->willReturn([]);
        $ticketRepository->add(Argument::type(Ticket::class))->shouldBeCalledTimes(2);
        $ticketRepository->save()->shouldBeCalled();
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

        return $versions;
    }

    private function mockIssues()
    {
        $issues = new Issues();
        $issue1 = new Issue();
        $issue1->id = '1234';
        $issue1->name = 'Issue 1';
        $issue1->status = 'state1';
        $issue1->description = 'description 1';

        $issue2 = new Issue();
        $issue2->id = '2';
        $issue2->name = 'Issue 2';
        $issue1->status = 'state2';
        $issue1->description = 'description 2';

        $issues->add($issue1);
        $issues->add($issue2);

        return $issues;
    }
}
