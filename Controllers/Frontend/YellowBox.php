<?php

use SM\SMException;
use SM\Factory\Factory;
use JodaYellowBox\Components\Ticket\TicketManager;

class Shopware_Controllers_Frontend_YellowBox extends Enlight_Controller_Action
{
    /**
     * @var Factory
     */
    protected $stateManager;

    /**
     * @var TicketManager
     */
    protected $ticketManager;

    public function init()
    {
        $this->stateManager = $this->get('joda_yellow_box.sm.factory');
        $this->ticketManager = $this->get('joda_yellow_box.services.ticket_manager');
    }

    public function indexAction()
    {
        $this->view->loadTemplate('frontend/joda_yellow_box/yellow_box.tpl');
    }

    /**
     * @return Enlight_View|Enlight_View_Default
     */
    public function transitionAction()
    {
        $this->view->loadTemplate('frontend/joda_yellow_box/yellow_box.tpl');

        $ticketId = (int) $this->request->get('ticketId');
        if ($this->ticketManager->existsTicket($ticketId) === false) {
            return $this->view->assign([
                'success' => false,
                'error' => 'ticket does not exists'
            ]);
        }

        $ticket = $this->ticketManager->getTicket($ticketId);
        $ticketTransition = $this->request->get('ticketTransition');

        try {
            $stateMaschine = $this->stateManager->get($ticket);
            $stateMaschine->apply($ticketTransition);
        } catch (SMException $ex) {
            // Invalid transition state
            return $this->view->assign([
                'success' => false,
                'error' => $ex->getMessage()
            ]);
        }

        // Success
        $this->getModelManager()->flush($ticket);
        return $this->view->assign('success', true);
    }
}
