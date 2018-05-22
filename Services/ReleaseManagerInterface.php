<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Models\Release;

interface ReleaseManagerInterface
{
    /**
     * Returns 'latest' when 'latest' is configured in plugin config and no corresponding release could be found
     * Returns the ticket name when it is not 'latest'
     *
     * @return string
     */
    public function getCurrentReleaseName(): string;

    /**
     * @return Release|null
     */
    public function getCurrentRelease();

    /**
     * Updates releases when remote API is set
     *
     * @param Project $project
     *
     * @throws ApiException
     */
    public function syncReleasesFromRemote();
}
