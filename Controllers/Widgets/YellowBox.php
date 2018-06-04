<?php

declare(strict_types=1);

use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Services\TicketServiceInterface;
use SM\Factory\Factory;

class Shopware_Controllers_Widgets_YellowBox extends Enlight_Controller_Action
{
    const SNAP_COOKIE = 'ybsnap';
    const MINIMIZE_COOKIE = 'ybmin';

    /**
     * @var Factory
     */
    protected $stateManager;

    /**
     * @var TicketServiceInterface
     */
    protected $ticketService;

    public function preDispatch()
    {
        $this->stateManager = $this->get('joda_yellow_box.sm.factory');
        $this->ticketService = $this->get('joda_yellow_box.services.ticket');
    }

    public function indexAction()
    {
        $snapCookie = $this->request->getCookie(self::SNAP_COOKIE);
        $minimizeCookie = $this->request->getCookie(self::MINIMIZE_COOKIE);

        $this->assignTicketDataToView();
        $this->view->assign('snapPosition', $snapCookie);
        $this->view->assign('isMinimized', $minimizeCookie);
    }

    public function transitionAction()
    {
        $this->view->loadTemplate('frontend/yellow_box/index.tpl');

        $ticketId = (int) $this->request->get('ticketId');
        if ($this->ticketService->existsTicket($ticketId) === false) {
            return $this->view->assign([
                'success' => false,
                'error' => 'ticket does not exists',
            ]);
        }

        $ticket = $this->ticketService->getTicket($ticketId);
        $ticketTransition = $this->request->get('ticketTransition');

        $comment = $this->request->get('comment');
        if ($comment) {
            $ticket->setComment($comment);
        }

        try {
            $this->ticketService->changeState($ticket, $ticketTransition);
        } catch (ChangeStateException $ex) {
            // Invalid transition state
            return $this->view->assign([
                'success' => false,
                'error' => $ex->getMessage(),
            ]);
        }

        // Success
        $this->getModelManager()->flush($ticket);

        $this->assignTicketDataToView();
        $this->view->assign('success', true);
    }

    protected function assignTicketDataToView()
    {
        $tickets = $this->ticketService->getCurrentTickets();

        $this->view->assign('releaseName', $this->ticketService->getCurrentReleaseName());
        $this->view->assign('tickets', $tickets);
    }
}
