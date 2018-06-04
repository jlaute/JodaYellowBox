<?php declare(strict_types=1);

namespace JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Models\ReleaseRepository;
use JodaYellowBox\Models\TicketRepository;
use JodaYellowBox\Services\ReleaseManagerInterface;

class TicketStrategyFactory
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

    /** @var string */
    private $releaseToDisplay;

    /**
     * @param ReleaseManagerInterface $releaseManager
     * @param ReleaseRepository       $releaseRepository
     * @param TicketRepository        $ticketRepository
     * @param ClientInterface         $client
     * @param string|null             $externalStatusId
     * @param string|null             $releaseToDisplay
     */
    public function __construct(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client,
        string $externalStatusId = null,
        string $releaseToDisplay = null
    ) {
        $this->releaseManager = $releaseManager;
        $this->releaseRepository = $releaseRepository;
        $this->ticketRepository = $ticketRepository;
        $this->client = $client;
        $this->externalStatusId = $externalStatusId;
        $this->releaseToDisplay = $releaseToDisplay;
    }

    /**
     * @param bool $ticketsDependOnRelease
     *
     * @return TicketStrategyInterface
     */
    public function getStrategy(bool $ticketsDependOnRelease): TicketStrategyInterface
    {
        if ($ticketsDependOnRelease) {
            return new ReleaseTicketStrategy(
                $this->releaseManager,
                $this->releaseRepository,
                $this->ticketRepository,
                $this->client,
                $this->releaseToDisplay
            );
        }

        return new StateTicketStrategy($this->client, $this->ticketRepository, $this->externalStatusId);
    }
}
