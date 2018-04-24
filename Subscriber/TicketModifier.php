<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;
use JodaYellowBox\Models\Ticket;
use SM\Factory\AbstractFactory as StateManagerFactory;

class TicketModifier implements SubscriberInterface
{
    /**
     * @var StateManagerFactory
     */
    private $stateManagerFactory;

    /**
     * @param StateManagerFactory $stateManagerFactory
     */
    public function __construct(StateManagerFactory $stateManagerFactory)
    {
        $this->stateManagerFactory = $stateManagerFactory;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'JodaYellowBox_Filter_Ticket' => 'onFilterTicket',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     *
     * @return array
     */
    public function onFilterTicket(\Enlight_Event_EventArgs $args)
    {
        $filterTicket = $ticket = $args->getReturn();

        if (!$filterTicket instanceof Ticket) {
            $ticket = new Ticket('');
            $ticket->fromArray($filterTicket, [
                'state',
            ]);
        }

        $stateMachine = $this->stateManagerFactory->get($ticket);
        $filterTicket['possibleTransitions'] = $stateMachine->getPossibleTransitions();

        return $filterTicket;
    }
}
