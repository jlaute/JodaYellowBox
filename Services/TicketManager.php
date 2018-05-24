<?php

declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Exception\ChangeStateException;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;
use SM\Factory\Factory as StateMachineFactory;
use SM\SMException;

class TicketManager implements TicketManagerInterface
{
    /** @var TicketRepository */
    protected $ticketRepository;

    /** @var StateMachineFactory */
    protected $stateMachineFactory;

    /** @var ClientInterface */
    protected $client;

    /** @var \Enlight_Event_EventManager */
    protected $eventManager;

    /** @var string */
    protected $externalStatusId;

    /**
     * @param TicketRepository            $ticketRepository
     * @param StateMachineFactory         $stateMachineFactory
     * @param ClientInterface             $client
     * @param \Enlight_Event_EventManager $eventManager
     * @param string                      $externalStatusId
     */
    public function __construct(
        TicketRepository $ticketRepository,
        StateMachineFactory $stateMachineFactory,
        ClientInterface $client,
        \Enlight_Event_EventManager $eventManager,
        string $externalStatusId = null
    ) {
        $this->ticketRepository = $ticketRepository;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->eventManager = $eventManager;
        $this->client = $client;
        $this->externalStatusId = $externalStatusId;
    }

    /**
     * {@inheritdoc}
     */
    public function getTicket($ident)
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

            $this->eventManager->notify('YellowBox_onChangeTicketState', [
                'subject' => $this,
                'state' => $state,
                'ticket' => $ticket,
            ]);
        } catch (SMException $e) {
            throw new ChangeStateException(
                sprintf('State "%s" for Ticket %s could not be applied!', $state, $ticket->getName())
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function syncTicketsFromRemote(Release $release)
    {
        if (!$release->getExternalId()) {
            throw new ApiException('Release `' . $release->getName() . '` has no External-ID');
        }

        $version = new Version();
        $version->id = $release->getExternalId();
        $issueStatus = new IssueStatus();
        $issueStatus->id = $this->externalStatusId;

        $issues = $this->client->getIssuesByVersion($version, $issueStatus);
        if (!$issues->valid()) {
            return;
        }

        $issueIds = [];
        foreach ($issues as $issue) {
            $issueIds[] = $issue->id;
        }

        $existingTickets = $this->ticketRepository->findByExternalIds($issueIds);
        foreach ($issues as $issue) {
            if (!$this->isIssueInTickets($issue, $existingTickets)) {
                $this->ticketRepository->add(
                    new Ticket($issue->name, null, $issue->description, $issue->id)
                );
            }
        }

        $this->ticketRepository->save();
    }

    /**
     * {@inheritdoc}
     */
    public function delete(Ticket $ticket)
    {
        $this->ticketRepository->remove($ticket);
    }

    /**
     * @param Issue          $issue
     * @param array|Ticket[] $tickets
     *
     * @return bool
     */
    protected function isIssueInTickets(Issue $issue, array $tickets): bool
    {
        foreach ($tickets as $ticket) {
            if ($issue->id === $ticket->getExternalId()) {
                return true;
            }
        }

        return false;
    }
}
