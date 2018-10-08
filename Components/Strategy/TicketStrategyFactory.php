<?php declare(strict_types=1);

namespace JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\Config\PluginConfig;
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

    /** @var PluginConfig */
    private $pluginConfig;

    /**
     * @param ReleaseManagerInterface $releaseManager
     * @param ReleaseRepository       $releaseRepository
     * @param TicketRepository        $ticketRepository
     * @param ClientInterface         $client
     * @param PluginConfig            $pluginConfig
     */
    public function __construct(
        ReleaseManagerInterface $releaseManager,
        ReleaseRepository $releaseRepository,
        TicketRepository $ticketRepository,
        ClientInterface $client,
        PluginConfig $pluginConfig
    ) {
        $this->releaseManager = $releaseManager;
        $this->releaseRepository = $releaseRepository;
        $this->ticketRepository = $ticketRepository;
        $this->client = $client;
        $this->pluginConfig = $pluginConfig;
        $this->externalStatusId = (string) $pluginConfig->get('JodaYellowBoxExternalStatusId');
        $this->releaseToDisplay = (string) $pluginConfig->get('JodaYellowBoxReleaseToDisplay');
    }

    /**
     * @return TicketStrategyInterface
     */
    public function getStrategy(): TicketStrategyInterface
    {
        $ticketsDependOnRelease = (bool) $this->pluginConfig->get('JodaYellowBoxTicketsDependOnRelease');
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
