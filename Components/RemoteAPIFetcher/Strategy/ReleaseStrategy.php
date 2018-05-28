<?php declare(strict_types=1);

namespace JodaYellowBox\Components\RemoteAPIFetcher\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;

class ReleaseStrategy implements StrategyInterface
{
    /** @var ReleaseManagerInterface */
    protected $releaseManager;

    /** @var ReleaseRepository */
    protected $releaseRepository;

    /** @var ClientInterface */
    protected $client;

    /** @var TicketRepository */
    protected $ticketRepository;

    /**
     * @param ReleaseManagerInterface $releaseManager
     * @param ReleaseRepository       $releaseRepository
     * @param TicketRepository        $ticketRepository
     * @param ClientInterface         $client
     */
    public function __construct(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client
    ) {
        $this->releaseManager = $releaseManager;
        $this->releaseRepository = $releaseRepository;
        $this->ticketRepository = $ticketRepository;
        $this->client = $client;
    }

    public function fetchData(Project $project)
    {
        $this->fetchReleases($project);
        $this->fetchTickets();
    }

    protected function fetchReleases(Project $project)
    {
        $versions = $this->client->getVersionsInProject($project);
        $versionIds = $versions->getAllVersionIds();

        $existingReleases = $this->releaseRepository->findByExternalIds($versionIds);
        foreach ($versions as $key => $version) {
            if (!$this->isVersionInReleases($version, $existingReleases)) {
                $this->releaseRepository->add(
                    new Release($version->name, $version->date, $version->id)
                );
            }
        }

        $this->releaseRepository->save();
    }

    protected function fetchTickets()
    {
        if (!$currentRelease = $this->releaseManager->getCurrentRelease()) {
            return;
        }

        $version = new Version();
        $version->id = $currentRelease->getExternalId();

        $issues = $this->client->getIssuesByVersion($version);
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
     * @param Version         $version
     * @param array|Release[] $releases
     *
     * @return bool
     */
    protected function isVersionInReleases(Version $version, array $releases): bool
    {
        foreach ($releases as $release) {
            if ($release->getExternalId() === $version->id) {
                return true;
            }
        }

        return false;
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
