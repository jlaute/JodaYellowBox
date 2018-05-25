<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber\StateMachine;

use JodaYellowBox\Components\NotificationCenter\NotificationCenterInterface;
use JodaYellowBox\Models\Ticket;
use SM\Event\TransitionEvent;

class Notifications
{
    /**
     * @var NotificationCenterInterface
     */
    private $notificationCenter;

    /**
     * @var \Enlight_Components_Snippet_Manager
     */
    private $snippetManager;

    /**
     * @param NotificationCenterInterface         $notificationCenter
     * @param \Enlight_Components_Snippet_Manager $snippetManager
     */
    public function __construct(
        NotificationCenterInterface $notificationCenter,
        \Enlight_Components_Snippet_Manager $snippetManager
    ) {
        $this->notificationCenter = $notificationCenter;
        $this->snippetManager = $snippetManager;
    }

    /**
     * @param Ticket          $ticket
     * @param TransitionEvent $event
     */
    public function onChangeTicketState(Ticket $ticket, TransitionEvent $event)
    {
        $newState = $ticket->getState();
        $snippets = $this->getNotificationSnippets();
        $message = '';

        if ($newState == Ticket::STATE_APPROVED) {
            $message = sprintf($snippets->get('approve_message'), $ticket->getNumber(), $ticket->getName());
        }

        if ($newState == Ticket::STATE_REOPENED) {
            $message = sprintf($snippets->get('reopen_message'), $ticket->getNumber(), $ticket->getName());
        }

        if ($newState == Ticket::STATE_REJECTED) {
            $message = sprintf($snippets->get('reject_message'), $ticket->getNumber(), $ticket->getName());
        }

        $this->notificationCenter->notify($message);
    }

    /**
     * @return \Enlight_Components_Snippet_Namespace
     */
    protected function getNotificationSnippets()
    {
        return $this->snippetManager->getNamespace('frontend/yellow_box/notification');
    }
}
