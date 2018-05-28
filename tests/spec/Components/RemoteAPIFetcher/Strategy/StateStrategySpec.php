<?php

namespace spec\JodaYellowBox\Components\RemoteAPIFetcher\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StateStrategy;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StrategyInterface;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class StateStrategySpec extends ObjectBehavior
{
    public function let(ClientInterface $client, TicketRepository $ticketRepository)
    {
        $this->beConstructedWith($client, $ticketRepository, '12');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(StateStrategy::class);
        $this->shouldImplement(StrategyInterface::class);
    }

    public function it_should_be_able_to_fetch_data(
        ClientInterface $client,
        TicketRepository $ticketRepository,
        Project $project
    ) {
        $issues = $this->mockIssues();
        $client->getIssuesByProject(Argument::type(Project::class), Argument::type(IssueStatus::class))->shouldBeCalled()->willReturn($issues);

        $ticketRepository->findByExternalIds(Argument::is(['1234', '2']))->shouldBeCalled()->willReturn([]);
        $ticketRepository->add(Argument::type(Ticket::class))->shouldBeCalledTimes(2);
        $ticketRepository->save()->shouldBeCalled();
        $this->fetchData($project);
    }

    private function mockIssues()
    {
        $issues = new Issues();
        $issue1 = new Issue();
        $issue1->id = '1234';
        $issue1->name = 'Issue 1';

        $issue2 = new Issue();
        $issue2->id = '2';
        $issue2->name = 'Issue 2';

        $issues->add($issue1);
        $issues->add($issue2);

        return $issues;
    }
}
