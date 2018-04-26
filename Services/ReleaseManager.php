<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;

class ReleaseManager implements ReleaseManagerInterface
{
    /**
     * @var ReleaseRepository
     */
    protected $releaseRepository;

    /**
     * @param ReleaseRepository $releaseRepository
     */
    public function __construct(ReleaseRepository $releaseRepository)
    {
        $this->releaseRepository = $releaseRepository;
    }

    /**
     * @return Release
     */
    public function getCurrentRelease(): Release
    {
        return $this->releaseRepository->findLatestRelease();
    }
}
