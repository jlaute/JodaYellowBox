<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Models\ReleaseRepository;

class ReleaseManager implements ReleaseManagerInterface
{
    /**
     * @var ReleaseRepository
     */
    protected $releaseRepository;

    /**
     * @var string
     */
    protected $releaseToDisplay;

    /**
     * @param ReleaseRepository $releaseRepository
     * @param string            $releaseToDisplay
     */
    public function __construct(
        ReleaseRepository $releaseRepository,
        string $releaseToDisplay
    ) {
        $this->releaseRepository = $releaseRepository;
        $this->releaseToDisplay = $releaseToDisplay;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentRelease()
    {
        if ($this->releaseToDisplay === 'latest') {
            return $this->releaseRepository->findLatestRelease();
        }

        return $this->releaseRepository->findReleaseByName($this->releaseToDisplay);
    }
}
