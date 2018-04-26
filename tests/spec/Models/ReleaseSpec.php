<?php

namespace spec\JodaYellowBox\Models;

use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shopware\Components\Model\ModelEntity;

class ReleaseSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('Release 1234');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Release::class);
        $this->shouldHaveType(ModelEntity::class);
    }

    public function it_get_id_returns_0_by_default()
    {
        $this->getId()->shouldReturn(0);
    }

    public function it_has_the_constructed_name()
    {
        $this->getName()->shouldReturn('Release 1234');
    }

    public function it_has_a_release_date()
    {
        $this->getReleaseDate()->shouldHaveType(\DateTime::class);
    }

    public function it_has_no_tickets_by_default()
    {
        $collection = $this->getTickets();
        $collection->count()->shouldReturn(0);
    }

    public function it_can_add_tickets(Ticket $ticket, Ticket $ticket2)
    {
        $this->addTicket($ticket);
        $this->getTickets()->contains($ticket)->shouldReturn(true);
        $this->getTickets()->count()->shouldReturn(1);

        $this->addTicket($ticket2);
        $this->getTickets()->contains($ticket2)->shouldReturn(true);
        $this->getTickets()->count()->shouldReturn(2);
    }

    public function it_does_not_add_tickets_twice(Ticket $ticket)
    {
        // Add first time
        $this->addTicket($ticket);
        $tickets = $this->getTickets();

        $tickets->contains($ticket)->shouldReturn(true);
        $tickets->count()->shouldReturn(1);

        // Add it again
        $this->addTicket($ticket);
        $tickets = $this->getTickets();

        $tickets->contains($ticket)->shouldReturn(true);
        $tickets->count()->shouldReturn(1);
    }

    public function it_can_remove_tickets(Ticket $ticket)
    {
        $this->addTicket($ticket);
        $this->removeTicket($ticket);
        $this->getTickets()->count()->shouldReturn(0);
    }
}
