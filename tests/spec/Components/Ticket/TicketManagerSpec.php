<?php

namespace spec\JodaYellowBox\Components\Ticket;

use Doctrine\ORM\EntityManagerInterface;
use JodaYellowBox\Components\Ticket\ChangeStateException;
use JodaYellowBox\Components\Ticket\TicketManager;
use JodaYellowBox\Components\Ticket\TicketManagerInterface;
use JodaYellowBox\Models\Repository;
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
        EntityManagerInterface $em,
        StateMachineFactory $stateMachineFactory,
        Repository $ticketRepository
    ) {
        $this->beConstructedWith($em, $stateMachineFactory);

        $em->getRepository(Ticket::class)->shouldBeCalled()->willReturn(
            $this->getTicketRepositoryMock($ticketRepository)
        );
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

    public function it_is_able_to_get_all_current_tickets() {
        $articles = [
            'fakeArticle'
        ];

        $this->getCurrentTickets()->shouldReturn($articles);
    }

    public function it_can_change_state_of_a_ticket(
        Ticket $ticket,
        StateMachineFactory $stateMachineFactory,
        StateMachineInterface $stateMachine
    )
    {
        $stateMachineFactory->get($ticket)->shouldBeCalled()->willReturn($stateMachine);
        $stateMachine->apply('approved')->shouldBeCalled();

        $this->changeState($ticket, 'approved');
    }

    public function it_throws_an_exception_when_invalid_state_should_be_applied(
        Ticket $ticket,
        StateMachineFactory $stateMachineFactory,
        StateMachineInterface $stateMachine
    )
    {
        $ticket->getName()->willReturn('TicketName');
        $stateMachineFactory->get($ticket)->shouldBeCalled()->willReturn($stateMachine);
        $stateMachine->beConstructedWith([]);
        $stateMachine->apply('asdf')->shouldBeCalled()->willThrow(SMException::class);

        $this->shouldThrow(ChangeStateException::class)->during('changeState', [$ticket, 'asdf']);
    }

    public function it_is_able_to_delete_a_ticket(
        Repository $ticketRepository,
        Ticket $ticket
    ) {
        $ticketRepository->remove($ticket)->shouldBeCalled();

        $this->deleteTicket($ticket);
    }

    /**
     * @param Repository $ticketRepository
     * @return Repository
     */
    protected function getTicketRepositoryMock(Repository $ticketRepository)
    {
        $ticketRepository->findTicket(Argument::any())->willReturn(new Ticket(''));
        $ticketRepository->existsTicket('exists')->willReturn(true);
        $ticketRepository->existsTicket('not exists')->willReturn(false);
        $ticketRepository->getCurrentTickets()->willReturn([
            'fakeArticle'
        ]);

        return $ticketRepository;
    }
}
