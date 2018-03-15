<?php
declare(strict_types=1);

namespace spec\JodaYellowBox\Models;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\Model\ModelEntity;
use SM\StateMachine\StateMachineInterface;

/**
 * @mixin \JodaYellowBox\Models\Ticket
 */
class TicketSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('name', 'number', 'description');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Ticket::class);
        $this->shouldHaveType(ModelEntity::class);
    }

    public function it_has_a_created_datetime_by_default()
    {
        $this->getCreatedAt()->shouldHaveType(\DateTime::class);
    }

    public function it_can_have_a_number()
    {
        $this->getNumber()->shouldReturn('number');
        $this->setNumber('ASDF-123');
        $this->getNumber()->shouldReturn('ASDF-123');
    }

    public function it_can_have_a_name()
    {
        $this->getName()->shouldReturn('name');
        $this->setName('Ticket for testing');
        $this->getName()->shouldReturn('Ticket for testing');
    }

    public function it_can_have_a_description()
    {
        $this->getDescription()->shouldReturn('description');
        $this->setDescription('This is a ticket description');
        $this->getDescription()->shouldReturn('This is a ticket description');
    }

    public function it_has_open_state_by_default()
    {
        $this->getState()->shouldReturn(Ticket::STATE_OPEN);
    }

    public function it_can_change_state_from_open_to_approved(StateMachineInterface $stateMachine)
    {
        $stateMachine->can(Argument::exact('approve'))->shouldBeCalled()->willReturn(true);
        $stateMachine->apply(Argument::exact('approve'))->shouldBeCalled()->willReturn(true);

        $this->approve($stateMachine);
    }

    public function it_can_change_state_from_open_to_rejected(StateMachineInterface $stateMachine)
    {
        $stateMachine->can(Argument::exact('reject'))->shouldBeCalled()->willReturn(true);
        $stateMachine->apply(Argument::exact('reject'))->shouldBeCalled()->willReturn(true);

        $this->reject($stateMachine);
    }

    public function it_can_change_state_from_rejected_to_reopened(StateMachineInterface $stateMachine)
    {
        $stateMachine->can(Argument::exact('reject'))->shouldBeCalled()->willReturn(true);
        $stateMachine->apply(Argument::exact('reject'))->shouldBeCalled()->willReturn(true);
        $this->reject($stateMachine);

        $stateMachine->can(Argument::exact('reopen'))->shouldBeCalled()->willReturn(true);
        $stateMachine->apply(Argument::exact('reopen'))->shouldBeCalled()->willReturn(true);
        $this->reopen($stateMachine);
    }

    public function it_cant_change_state_from_approved_to_rejected(StateMachineInterface $stateMachine)
    {
        $stateMachine->can(Argument::exact('approve'))->shouldBeCalled()->willReturn(true);
        $stateMachine->apply(Argument::exact('approve'))->shouldBeCalled()->willReturn(true);
        $this->approve($stateMachine);

        $stateMachine->can(Argument::exact('reject'))->shouldBeCalled();
        $stateMachine->apply(Argument::exact('reject'))->shouldNotBeCalled();
        $this->reject($stateMachine);
    }
}
