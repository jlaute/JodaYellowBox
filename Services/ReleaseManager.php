<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\ReleaseRepository;

class ReleaseManager implements ReleaseManagerInterface
{
    /**
     * @var ReleaseRepository
     */
    protected $releaseRepository;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var string
     */
    protected $releaseToDisplay;

    /**
     * @param ReleaseRepository $releaseRepository
     * @param ClientInterface   $client
     * @param string            $releaseToDisplay
     */
    public function __construct(
        ReleaseRepository $releaseRepository,
        ClientInterface $client,
        string $releaseToDisplay
    ) {
        $this->releaseRepository = $releaseRepository;
        $this->client = $client;
        $this->releaseToDisplay = $releaseToDisplay;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentReleaseName(): string
    {
        if ($this->releaseToDisplay !== 'latest') {
            return $this->releaseToDisplay;
        }

        $latestRelease = $this->releaseRepository->findLatestRelease();
        if ($latestRelease) {
            return $latestRelease->getName();
        }

        return 'latest';
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

    /**
     * {@inheritdoc}
     */
    public function syncReleasesFromRemote(Project $project)
    {
        $versions = $this->client->getVersionsInProject($project);
        $versionIds = [];
        foreach ($versions as $version) {
            $versionIds[] = $version->id;
        }

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
}
