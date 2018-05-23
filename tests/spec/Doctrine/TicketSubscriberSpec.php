<?php

namespace spec\JodaYellowBox\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use JodaYellowBox\Doctrine\TicketSubscriber;
use JodaYellowBox\Models\Ticket;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TicketSubscriberSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TicketSubscriber::class);
        $this->shouldImplement(EventSubscriber::class);
    }

    public function it_tracks_state_changes_in_separate_table(
        PreUpdateEventArgs $args,
        Ticket $ticket,
        EntityManager $em,
        Connection $con
    ) {
        $args->getObject()->shouldBeCalled()->willReturn($ticket);
        $args->hasChangedField(Argument::exact(TicketSubscriber::STATE_PROPERTY))->shouldBeCalled()->willReturn(true);
        $args->getOldValue(Argument::exact(TicketSubscriber::STATE_PROPERTY))->shouldBeCalled()->willReturn('old');
        $args->getNewValue(Argument::exact(TicketSubscriber::STATE_PROPERTY))->shouldBeCalled()->willReturn('new');

        $args->getEntityManager()->shouldBeCalled()->willReturn($em);
        $em->getConnection()->shouldBeCalled()->willReturn($con);
        $ticket->getId()->shouldBeCalled()->willReturn(1);

        $con->insert(Argument::exact('s_plugin_yellow_box_ticket_history'), Argument::type('array'))->shouldBeCalled();

        $this->preUpdate($args);
    }
}
