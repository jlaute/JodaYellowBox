<?php declare(strict_types=1);

namespace JodaYellowBox\Components\RemoteAPIFetcher\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;

class StateStrategy implements StrategyInterface
{
    /** @var ClientInterface */
    private $client;

    /** @var TicketRepository */
    private $ticketRepository;

    /** @var string */
    private $externalStatusId;

    public function __construct(ClientInterface $client, TicketRepository $ticketRepository, string $externalStatusId)
    {
        $this->client = $client;
        $this->ticketRepository = $ticketRepository;
        $this->externalStatusId = $externalStatusId;
    }

    public function fetchData(Project $project)
    {
        $this->fetchTickets($project);
    }

    public function fetchTickets(Project $project)
    {
        $issueStatus = new IssueStatus();
        $issueStatus->id = $this->externalStatusId;

        $issues = $this->client->getIssuesByProject($project, $issueStatus);
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
                new Ticket($issue->name, null, $issue->description, $issue->id)
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
                $ticket->setDescription($issue->description);
                $ticket->setName($issue->name);

                return $ticket;
            }
        }

        return null;
    }
}
