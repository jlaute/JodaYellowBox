<?php

declare(strict_types=1);

use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Services\TicketManager;
use SM\Factory\Factory;

class Shopware_Controllers_Widgets_YellowBox extends Enlight_Controller_Action
{
    /**
     * @var Factory
     */
    protected $stateManager;

    /**
     * @var TicketManager
     */
    protected $ticketManager;

    public function preDispatch()
    {
        $this->stateManager = $this->get('joda_yellow_box.sm.factory');
        $this->ticketManager = $this->get('joda_yellow_box.services.ticket_manager');
    }

    public function indexAction()
    {
    }

    public function transitionAction()
    {
        $this->view->loadTemplate('frontend/yellow_box/index.tpl');

        $ticketId = (int) $this->request->get('ticketId');
        if ($this->ticketManager->existsTicket($ticketId) === false) {
            return $this->view->assign([
                'success' => false,
                'error' => 'ticket does not exists',
            ]);
        }

        $ticket = $this->ticketManager->getTicket($ticketId);
        $ticketTransition = $this->request->get('ticketTransition');

        $comment = $this->request->get('comment');
        if ($comment) {
            $ticket->setComment($comment);
        }

        try {
            $this->ticketManager->changeState($ticket, $ticketTransition);
        } catch (ChangeStateException $ex) {
            // Invalid transition state
            return $this->view->assign([
                'success' => false,
                'error' => $ex->getMessage(),
            ]);
        }

        // Success
        $this->getModelManager()->flush($ticket);

        return $this->view->assign('success', true);
    }
}