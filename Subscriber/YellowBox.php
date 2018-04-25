<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;

class YellowBox implements SubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch',
        ];
    }

    /**
     * @param \Enlight_Controller_ActionEventArgs $args
     */
    public function onFrontendPostDispatch(\Enlight_Controller_ActionEventArgs $args)
    {
        $controller = $args->getSubject();
        $ticketManager = $controller->get('joda_yellow_box.services.ticket_manager');

        $view = $controller->View();
        $currentTickets = $ticketManager->getCurrentTickets();
        foreach ($currentTickets as $key => $ticket) {
            $currentTickets[$key]->setPossibleTransitions($ticketManager->getPossibleTransitions($ticket));
        }
        $view->assign('currentTickets', $currentTickets);
    }
}
