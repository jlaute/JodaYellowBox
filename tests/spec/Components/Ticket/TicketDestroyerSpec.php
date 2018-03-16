<?php

namespace spec\JodaYellowBox\Components\Ticket;

use JodaYellowBox\Components\Ticket\TicketDestroyer;
use JodaYellowBox\Components\Ticket\TicketNotExistException;
use JodaYellowBox\Models\Repository;
use JodaYellowBox\Models\Ticket;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shopware\Components\Model\ModelManager;

/**
 * @mixin TicketDestroyer
 */
class TicketDestroyerSpec extends ObjectBehavior
{
    public function let(ModelManager $em)
    {
        $this->beConstructedWith($em);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TicketDestroyer::class);
    }

    public function it_cant_delete_not_existing_tickets(
        ModelManager $em,
        Repository $repository
    ) {
        $em->getRepository(Ticket::class)->willReturn($repository);
        $repository->getTicketByName('ticket name')->willReturn(null);

        $this->shouldThrow(TicketNotExistException::class)->during('deleteTicket', ['ticket name']);
    }

    public function it_is_able_to_delete_a_ticket(
        ModelManager $em,
        Repository $repository,
        Ticket $ticket
    ) {
        $em->getRepository(Ticket::class)->willReturn($repository);
        $repository->getTicketByName('ticket name')->willReturn($ticket);

        $em->remove(Argument::type(Ticket::class))->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->deleteTicket('ticket name')->shouldReturnAnInstanceOf(Ticket::class);
    }
}
