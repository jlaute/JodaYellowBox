<?php declare(strict_types=1);
use JodaYellowBox\Components\Ticket\TicketManager;
use SM\Factory\Factory;
use SM\SMException;

class Shopware_Controllers_Frontend_YellowBox extends \Enlight_Controller_Action
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

    /**
     * @return Enlight_View|Enlight_View_Default
     */
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

        try {
            $this->ticketManager->changeState($ticket, $ticketTransition);
        } catch (\JodaYellowBox\Components\Ticket\ChangeStateException $ex) {
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
