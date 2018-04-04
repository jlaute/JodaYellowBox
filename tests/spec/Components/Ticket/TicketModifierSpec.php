<?php

namespace spec\JodaYellowBox\Components\Ticket;

use JodaYellowBox\Components\Ticket\TicketModifier;
use JodaYellowBox\Components\Ticket\TicketModifierInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @mixin TicketModifier
 */
class TicketModifierSpec extends ObjectBehavior
{

    public function let(\Enlight_Event_EventManager $eventManager)
    {
        $this->beConstructedWith($eventManager);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TicketModifier::class);
    }

    public function it_is_ticket_modifier()
    {
        $this->shouldImplement(TicketModifierInterface::class);
    }

    public function it_should_return_empty_parameter()
    {
        $this->modify([])->shouldReturn([]);
    }

    public function it_is_able_to_modify_a_ticket(
        \Enlight_Event_EventManager $eventManager
    ) {
        $article = ['test'];
        $modifiedArticle = ['test', 'modified'];

        $eventManager
            ->filter('JodaYellowBox_Filter_Ticket', $article)
            ->willReturn($modifiedArticle);
        
        $this->modify([
            $article
        ])->shouldReturn([
            $modifiedArticle
        ]);
    }
}
