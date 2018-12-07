<?php declare(strict_types=1);

namespace JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;

class StateTicketStrategy implements TicketStrategyInterface
{
    /** @var ClientInterface */
    private $client;

    /** @var TicketRepository */
    private $ticketRepository;

    /** @var string */
    private $externalStatusId;

    /**
     * @param ClientInterface  $client
     * @param TicketRepository $ticketRepository
     * @param string           $externalStatusId
     */
    public function __construct(ClientInterface $client, TicketRepository $ticketRepository, string $externalStatusId)
    {
        $this->client = $client;
        $this->ticketRepository = $ticketRepository;
        $this->externalStatusId = $externalStatusId;
    }

    /**
     * {@inheritdoc}
     */
    public function fetchData(Project $project)
    {
        $this->fetchTickets($project);
    }

    public function getCurrentTickets(): array
    {
        return $this->ticketRepository->findByExternalStateId($this->externalStatusId);
    }

    public function getCurrentReleaseName(): string
    {
        return '';
    }

    /**
     * @param Project $project
     *
     * @throws \JodaYellowBox\Components\API\ApiException
     */
    protected function fetchTickets(Project $project)
    {
        $issues = $this->client->getIssuesByProject($project);
        if (!$issues->valid()) {
            return;
        }

        $issueIds = $issues->getAllIssueIds();

        $existingTickets = $this->ticketRepository->findByExternalIds($issueIds);
        foreach ($issues as $issue) {
            if ($ticket = $this->getTicketForIssue($issue, $existingTickets)) {
                $this->ticketRepository->add($ticket);
                continue;
            }

            $this->ticketRepository->add(
                new Ticket($issue->name, $issue->number, $issue->description, $issue->id, $issue->status)
            );
        }

        $this->ticketRepository->save();
    }

    /**
     * @param Issue          $issue
     * @param array|Ticket[] $tickets
     *
     * @return Ticket|null
     */
    protected function getTicketForIssue(Issue $issue, array $tickets)
    {
        foreach ($tickets as $ticket) {
            if ($issue->id === $ticket->getExternalId()) {
                $ticket->setExternalState($issue->status);
                $ticket->setName($issue->name);
                if ($issue->description) {
                    $ticket->setDescription($issue->description);
                }
                if ($issue->number) {
                    $ticket->setNumber($issue->number);
                }

                return $ticket;
            }
        }

        return null;
    }
}
