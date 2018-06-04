<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\Strategy\TicketStrategyInterface;
use JodaYellowBox\Models\Ticket;

class TicketService implements TicketServiceInterface
{
    /** @var TicketStrategyInterface */
    protected $ticketStrategy;

    /** @var ReleaseManagerInterface */
    protected $releaseManager;

    /** @var TicketManagerInterface */
    protected $ticketManager;

    /** @var string */
    protected $externalProjectId;

    /**
     * @param TicketStrategyInterface $ticketStrategy
     * @param ReleaseManagerInterface $releaseManager
     * @param TicketManagerInterface  $ticketManager
     * @param string                  $externalProjectId
     */
    public function __construct(
        TicketStrategyInterface $ticketStrategy,
        ReleaseManagerInterface $releaseManager,
        TicketManagerInterface $ticketManager,
        string $externalProjectId = ''
    ) {
        $this->ticketStrategy = $ticketStrategy;
        $this->releaseManager = $releaseManager;
        $this->ticketManager = $ticketManager;
        $this->externalProjectId = $externalProjectId;
    }

    /**
     * {@inheritdoc}
     */
    public function syncRemoteData()
    {
        $project = new Project();
        $project->id = $this->externalProjectId;

        $this->ticketStrategy->fetchData($project);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentTickets(): array
    {
        return $this->ticketStrategy->getCurrentTickets();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentReleaseName(): string
    {
        return $this->ticketStrategy->getCurrentReleaseName();
    }

    /**
     * {@inheritdoc}
     */
    public function getTicket($ident)
    {
        return $this->ticketManager->getTicket($ident);
    }

    /**
     * {@inheritdoc}
     */
    public function existsTicket($ident): bool
    {
        return $this->ticketManager->existsTicket($ident);
    }

    /**
     * {@inheritdoc}
     */
    public function changeState(Ticket $ticket, string $state)
    {
        $this->ticketManager->changeState($ticket, $state);
    }
}
