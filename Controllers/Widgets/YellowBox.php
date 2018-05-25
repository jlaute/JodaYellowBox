<?php

declare(strict_types=1);

use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Services\ReleaseManagerInterface;
use JodaYellowBox\Services\TicketManagerInterface;
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
     * @var TicketManagerInterface
     */
    protected $ticketManager;

    /**
     * @var ReleaseManagerInterface
     */
    protected $releaseManager;

    public function preDispatch()
    {
        $this->stateManager = $this->get('joda_yellow_box.sm.factory');
        $this->ticketManager = $this->get('joda_yellow_box.services.ticket_manager');
        $this->releaseManager = $this->get('joda_yellow_box.services.release_manager');
    }

    public function indexAction()
    {
        $view = $this->View();

        $currentRelease = $this->releaseManager->getCurrentRelease();
        $snapCookie = $this->request->getCookie(self::SNAP_COOKIE);
        $minimizeCookie = $this->request->getCookie(self::MINIMIZE_COOKIE);

        $view->assign('currentRelease', $currentRelease);
        $view->assign('snapPosition', $snapCookie);
        $view->assign('isMinimized', $minimizeCookie);
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

        $currentRelease = $this->releaseManager->getCurrentRelease();
        $this->view->assign('currentRelease', $currentRelease);
        $this->view->assign('success', true);
    }
}
