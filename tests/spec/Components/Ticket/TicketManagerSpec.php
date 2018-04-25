<?php

namespace spec\JodaYellowBox\Components\Ticket;

use Doctrine\ORM\EntityManagerInterface;
use JodaYellowBox\Components\Ticket\TicketManager;
use JodaYellowBox\Components\Ticket\TicketManagerInterface;
use JodaYellowBox\Models\Repository;
use JodaYellowBox\Models\Ticket;
use SM\Factory\Factory as StateMachineFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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
