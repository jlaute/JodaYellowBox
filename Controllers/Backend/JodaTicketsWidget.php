<?php declare(strict_types=1);
/*
 * © isento eCommerce solutions GmbH
 */

use JodaYellowBox\Exception\ChangeStateException;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@isento-ecommerce.de>
 * @copyright 2018 isento eCommerce solutions GmbH (http://www.isento-ecommerce.de)
 */
class Shopware_Controllers_Backend_JodaTicketsWidget extends Shopware_Controllers_Backend_ExtJs
{
    public function listAction()
    {
        $ticketService = $this->get('joda_yellow_box.services.ticket');
        $tickets = $ticketService->getCurrentTickets();

        $viewTickets = [];
        foreach ($tickets as $ticket) {
            $viewTickets[] = $ticket->toArray();
        }

        $this->View()->assign([
            'success' => true,
            'data' => $viewTickets,
            'total' => count($viewTickets),
        ]);
    }

    public function transitionAction()
    {
        $ticketService = $this->get('joda_yellow_box.services.ticket');
        $ticketId = (int) $this->request->get('id');
        $ticket = $ticketService->getTicket($ticketId);

        $transition = $this->request->get('transition');

        try {
            $ticketService->changeState($ticket, $transition);
        } catch (ChangeStateException $ex) {
            // Invalid transition state
            return $this->view->assign([
                'success' => false,
                'error' => $ex->getMessage(),
            ]);
        }

        $this->get('models')->persist($ticket);
        $this->get('models')->flush($ticket);

        $this->view->assign('success', true);
    }
}
