<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\ApiException;
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
     * @param string $name
     *
     * @return Release|null
     */
    public function getReleaseByName(string $name);

    /**
     * Updates releases when remote API is set
     *
     * @throws ApiException
     */
    public function syncReleasesFromRemote();
}
