<?php declare(strict_types=1);

namespace JodaYellowBox\Components\RemoteAPIFetcher;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\ReleaseStrategy;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StateStrategy;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StrategyInterface;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;

class StrategyFactory
{
    /** @var ReleaseManagerInterface */
    private $releaseManager;

    /** @var ReleaseRepository */
    private $releaseRepository;

    /** @var TicketRepository */
    private $ticketRepository;

    /** @var ClientInterface */
    private $client;

    /** @var string */
    private $externalStatusId;

    /**
     * @param ReleaseManagerInterface $releaseManager
     * @param ReleaseRepository       $releaseRepository
     * @param TicketRepository        $ticketRepository
     * @param ClientInterface         $client
     * @param string|null             $externalStatusId
     */
    public function __construct(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client,
        string $externalStatusId = null
    ) {
        $this->releaseManager = $releaseManager;
        $this->releaseRepository = $releaseRepository;
        $this->ticketRepository = $ticketRepository;
        $this->client = $client;
        $this->externalStatusId = $externalStatusId;
    }

    /**
     * @param bool $ticketsDependOnRelease
     *
     * @return StrategyInterface
     */
    public function getStrategy(bool $ticketsDependOnRelease): StrategyInterface
    {
        if ($ticketsDependOnRelease) {
            return new ReleaseStrategy(
                $this->releaseManager,
                $this->releaseRepository,
                $this->ticketRepository,
                $this->client
            );
        }

        return new StateStrategy($this->client, $this->ticketRepository, $this->externalStatusId);
    }
}
