<?php
declare(strict_types=1);

namespace spec\JodaYellowBox\Models;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\Model\ModelEntity;

/**
 * @mixin \JodaYellowBox\Models\Ticket
 */
class TicketSpec extends ObjectBehavior
{
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
        $this->setNumber('ASDF-123');
        $this->getNumber()->shouldReturn('ASDF-123');
    }

    public function it_can_have_a_name()
    {
        $this->setName('Ticket for testing');
        $this->getName()->shouldReturn('Ticket for testing');
    }

    public function it_can_have_a_description()
    {
        $this->setDescription('This is a ticket description');
        $this->getDescription()->shouldReturn('This is a ticket description');
    }
}
