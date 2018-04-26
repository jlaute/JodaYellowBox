<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\Config\PluginConfigInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;

class ReleaseManager implements ReleaseManagerInterface
{
    /**
     * @var ReleaseRepository
     */
    protected $releaseRepository;

    /**
     * @var PluginConfigInterface
     */
    protected $config;

    /**
     * @param ReleaseRepository     $releaseRepository
     * @param PluginConfigInterface $config
     */
    public function __construct(ReleaseRepository $releaseRepository, PluginConfigInterface $config)
    {
        $this->releaseRepository = $releaseRepository;
        $this->config = $config;
    }

    /**
     * Returns 'latest' when 'latest' is configured in plugin config and no corresponding release could be found
     * Returns the ticket name when it is not 'latest'
     *
     * @return string
     */
    public function getCurrentReleaseName(): string
    {
        $releaseToDisplay = $this->config->getReleaseToDisplay();

        if ($releaseToDisplay !== 'latest') {
            return $releaseToDisplay;
        }

        $latestRelease = $this->releaseRepository->findLatestRelease();
        if ($latestRelease) {
            return $latestRelease->getName();
        }

        return 'latest';
    }

    /**
     * @return Release|null
     */
    public function getCurrentRelease()
    {
        $releaseToDisplay = $this->config->getReleaseToDisplay();

        if ($releaseToDisplay === 'latest') {
            return $this->releaseRepository->findLatestRelease();
        }

        return $this->releaseRepository->findReleaseByName($releaseToDisplay);
    }
}
