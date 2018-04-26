<?php

declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;
use SM\Factory\Factory as StateMachineFactory;
use SM\SMException;

class TicketManager implements TicketManagerInterface
{
    /**
     * @var TicketRepository
     */
    protected $ticketRepository;

    /**
     * @var StateMachineFactory
     */
    protected $stateMachineFactory;

    /**
     * @param TicketRepository    $ticketRepository
     * @param StateMachineFactory $stateMachineFactory
     */
    public function __construct(TicketRepository $ticketRepository, StateMachineFactory $stateMachineFactory)
    {
        $this->ticketRepository = $ticketRepository;
        $this->stateMachineFactory = $stateMachineFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getTicket($ident): Ticket
    {
        return $this->ticketRepository->findTicket($ident);
    }

    /**
     * {@inheritdoc}
     */
    public function existsTicket($ident): bool
    {
        return $this->ticketRepository->existsTicket($ident);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentTickets(): array
    {
        return $this->ticketRepository->getCurrentTickets();
    }

    /**
     * {@inheritdoc}
     */
    public function getPossibleTransitions(Ticket $ticket): array
    {
        $stateMachine = $this->stateMachineFactory->get($ticket);

        return $stateMachine->getPossibleTransitions();
    }

    /**
     * {@inheritdoc}
     */
    public function changeState(Ticket $ticket, string $state)
    {
        try {
            $stateMachine = $this->stateMachineFactory->get($ticket);
            $stateMachine->apply($state);
        } catch (SMException $e) {
            throw new ChangeStateException(
                sprintf('State "%s" for Ticket %s could not be applied!', $state, $ticket->getName())
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Ticket $ticket)
    {
        $this->ticketRepository->remove($ticket);
    }
}
