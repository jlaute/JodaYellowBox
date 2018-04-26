<?php

declare(strict_types=1);

namespace JodaYellowBox\Services;

use Doctrine\ORM\EntityManagerInterface;
use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Models\Repository;
use JodaYellowBox\Models\Ticket;
use SM\Factory\Factory as StateMachineFactory;
use SM\SMException;

class TicketManager implements TicketManagerInterface
{
    /**
     * @var Repository
     */
    protected $ticketRepository;

    /**
     * @var StateMachineFactory
     */
    protected $stateMachineFactory;

    /**
     * @param EntityManagerInterface $em
     * @param StateMachineFactory    $stateMachineFactory
     */
    public function __construct(EntityManagerInterface $em, StateMachineFactory $stateMachineFactory)
    {
        $this->ticketRepository = $em->getRepository(Ticket::class);
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
