<?php declare(strict_types=1);

namespace spec\JodaYellowBox\Services;

use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\TicketCreator;
use JodaYellowBox\Models\Ticket;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
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

    public function it_can_create_new_ticket(
        ModelManager $em,
        TicketRepository $repository
    ) {
        $em->getRepository(Ticket::class)->willReturn($repository);
        $em->persist(Argument::type(Ticket::class))->shouldBeCalled();
        $em->flush()->shouldBeCalled();

        $this->createTicket('ticket name')->shouldReturnAnInstanceOf(Ticket::class);
    }
}
