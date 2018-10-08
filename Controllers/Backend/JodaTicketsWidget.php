<?php declare(strict_types=1);
/**
 * © isento eCommerce solutions GmbH
 */

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
}
