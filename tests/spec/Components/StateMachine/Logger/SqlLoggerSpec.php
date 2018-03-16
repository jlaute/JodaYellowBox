<?php

namespace spec\JodaYellowBox\Components\StateMachine\Logger;

use JodaYellowBox\Components\StateMachine\Logger\SqlLogger;
use JodaYellowBox\Components\StateMachine\Logger\StateLogger;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketHistory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shopware\Components\Model\ModelManager;
use SM\Event\TransitionEvent;

class SqlLoggerSpec extends ObjectBehavior
{
    public function let(ModelManager $modelManager)
    {
        $this->beConstructedWith($modelManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SqlLogger::class);
        $this->shouldImplement(StateLogger::class);
    }

    public function it_will_safe_state_changes_to_db(
        Ticket $ticket,
        TransitionEvent $transitionEvent,
        ModelManager $modelManager
    )
    {
        $ticket->getId()->shouldBeCalled()->willReturn(1);
        $transitionEvent->getState()->shouldBeCalled()->willReturn('oldState');
        $ticket->getState()->shouldBeCalled()->willReturn('newState');

        $modelManager->persist(Argument::type(TicketHistory::class))->shouldBeCalled();
        $modelManager->flush(Argument::type(TicketHistory::class))->shouldBeCalled();

        $this->log($ticket, $transitionEvent);
    }

    public function it_does_interrupt_with_empty_ticket_id(
        Ticket $ticket,
        TransitionEvent $transitionEvent,
        ModelManager $modelManager
    )
    {
        $ticket->getId()->shouldBeCalled()->willReturn(0);
        $modelManager->persist(Argument::type(TicketHistory::class))->shouldNotBeCalled();

        $this->log($ticket, $transitionEvent);
    }
}
