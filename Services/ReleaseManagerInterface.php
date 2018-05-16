<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

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
    public function getCurrentRelease(): Release;

    /**
     * Updates releases when remote API is set
     */
    public function syncReleasesFromRemote();
}
