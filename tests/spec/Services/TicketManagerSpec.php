<?php

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\TicketManager;
use JodaYellowBox\Services\TicketManagerInterface;
use JodaYellowBox\Models\Ticket;
use SM\Factory\Factory as StateMachineFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use SM\SMException;
use SM\StateMachine\StateMachineInterface;

/**
 * @mixin TicketManager
 */
class TicketManagerSpec extends ObjectBehavior
{
    public function let(
        TicketRepository $ticketRepository,
        StateMachineFactory $stateMachineFactory,
        ClientInterface $client,
        \Enlight_Event_EventManager $eventManager
    ) {
        $this->beConstructedWith($ticketRepository, $stateMachineFactory, $client, $eventManager);
        $this->getTicketRepositoryMock($ticketRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TicketManager::class);
        $this->shouldBeAnInstanceOf(TicketManagerInterface::class);
    }

    public function it_is_able_to_get_a_ticket()
    {
        $this->getTicket(Argument::Any())->shouldReturnAnInstanceOf(Ticket::class);
    }

    public function it_is_able_to_check_if_a_ticket_exists()
    {
        $this->existsTicket('exists')->shouldReturn(true);
        $this->existsTicket('not exists')->shouldReturn(false);
    }

    public function it_is_able_to_get_all_current_tickets()
    {
        $articles = [
            'fakeArticle'
        ];

        $this->getCurrentTickets()->shouldReturn($articles);
    }

    public function it_can_change_state_of_a_ticket(
        Ticket $ticket,
        StateMachineFactory $stateMachineFactory,
        StateMachineInterface $stateMachine
    ) {
        $stateMachineFactory->get($ticket)->shouldBeCalled()->willReturn($stateMachine);
        $stateMachine->apply('approved')->shouldBeCalled();

        $this->changeState($ticket, 'approved');
    }

    public function it_throws_an_exception_when_invalid_state_should_be_applied(
        Ticket $ticket,
        StateMachineFactory $stateMachineFactory,
        StateMachineInterface $stateMachine
    ) {
        $ticket->getName()->willReturn('TicketName');
        $stateMachineFactory->get($ticket)->shouldBeCalled()->willReturn($stateMachine);
        $stateMachine->beConstructedWith([]);
        $stateMachine->apply('asdf')->shouldBeCalled()->willThrow(SMException::class);

        $this->shouldThrow(ChangeStateException::class)->during('changeState', [$ticket, 'asdf']);
    }

    public function it_is_able_to_delete_a_ticket(
        TicketRepository $ticketRepository,
        Ticket $ticket
    ) {
        $ticketRepository->remove($ticket)->shouldBeCalled();

        $this->delete($ticket);
    }

    public function it_can_sync_tickets_from_remote(
        TicketRepository $ticketRepository,
        ClientInterface $client,
        Release $release
    ) {
        $release->getExternalId()->shouldBeCalled()->willReturn('12');

        $issues = $this->mockIssues();
        $client->getIssuesByVersion(Argument::type(Version::class), Argument::type(IssueStatus::class))->shouldBeCalled()->willReturn($issues);

        $ticketRepository->findByExternalIds(Argument::type('array'))->shouldBeCalled()->willReturn([]);
        $ticketRepository->add(Argument::type(Ticket::class))->shouldBeCalled();
        $ticketRepository->save()->shouldBeCalled();
        $this->syncTicketsFromRemote($release);
    }

    public function it_does_not_duplicate_tickets(
        TicketRepository $ticketRepository,
        ClientInterface $client,
        Release $release
    ) {
        $release->getExternalId()->shouldBeCalled()->willReturn('12');

        $issues = $this->mockIssues();
        $client->getIssuesByVersion(Argument::type(Version::class), Argument::type(IssueStatus::class))->shouldBeCalled()->willReturn($issues);

        $tickets = $this->mockTickets();
        $ticketRepository->findByExternalIds(Argument::type('array'))->shouldBeCalled()->willReturn($tickets);
        $ticketRepository->add(Argument::type(Ticket::class))->shouldBeCalledTimes(1);
        $ticketRepository->save()->shouldBeCalled();
        $this->syncTicketsFromRemote($release);
    }

    /**
     * @param TicketRepository $ticketRepository
     * @return TicketRepository
     */
    protected function getTicketRepositoryMock(TicketRepository $ticketRepository)
    {
        $ticketRepository->findTicket(Argument::any())->willReturn(new Ticket(''));
        $ticketRepository->existsTicket('exists')->willReturn(true);
        $ticketRepository->existsTicket('not exists')->willReturn(false);
        $ticketRepository->getCurrentTickets()->willReturn([
            'fakeArticle'
        ]);

        return $ticketRepository;
    }

    protected function mockIssues()
    {
        $issues = new Issues();

        $issue1 = new Issue();
        $issue1->id = '12';
        $issue1->name = 'name1';
        $issue1->description = 'desc1';

        $issue2 = new Issue();
        $issue2->id = '13';
        $issue2->name = 'name2';
        $issue2->description = 'desc2';

        $issues->add($issue1);
        $issues->add($issue2);

        return $issues;
    }

    protected function mockTickets()
    {
        $tickets[] = new Ticket('name1', null, null, '12');
        $tickets[] = new Ticket('name2', null, null, '3');

        return $tickets;
    }
}
