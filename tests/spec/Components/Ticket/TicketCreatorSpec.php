<?php

namespace spec\JodaYellowBox\Components\Ticket;

use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\Repository;
use JodaYellowBox\Components\Ticket\TicketCreator;
use JodaYellowBox\Components\Ticket\TicketAlreadyExistException;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Shopware\Components\Model\ModelManager;

/**
 * @mixin TicketCreator
 */
class TicketCreatorSpec extends ObjectBehavior
{
    public function let(ModelManager $em)
    {
        $this->beConstructedWith($em);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TicketCreator::class);
    }

    public function it_doesnt_allow_duplicate_tickets(
        ModelManager $em,
        Repository $repository
    ) {
        $em->getRepository(Ticket::class)->willReturn($repository);
        $repository->existsTicket('ticket name')->shouldBeCalled()->willReturn(true);

        $this->shouldThrow(TicketAlreadyExistException::class)->during('createTicket', ['ticket name']);
    }

    public function it_can_create_new_ticket(
        ModelManager $em,
        Repository $repository
    ) {
        $em->getRepository(Ticket::class)->willReturn($repository);
        $repository->existsTicket('ticket name')->shouldBeCalled()->willReturn(false);

        $em->persist(Argument::type(Ticket::class))->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->createTicket('ticket name')->shouldReturnAnInstanceOf(Ticket::class);
    }
}
