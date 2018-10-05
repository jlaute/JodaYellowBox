<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Client;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;

interface ClientInterface
{
    const REQUEST_FORMAT = 'json';

    /**
     * get all project specific issues
     *
     * @param Project          $project
     * @param IssueStatus|null $issueStatus
     * @param int              $offset
     * @param int              $limit
     *
     * @throws ApiException
     *
     * @return Issues
     */
    public function getIssuesByProject(
        Project $project,
        IssueStatus $issueStatus = null,
        $offset = 0,
        $limit = 100
    ): Issues;

    /**
     * @param Version     $version
     * @param Project     $project
     * @param IssueStatus $issueStatus
     * @param int         $offset
     * @param int         $limit
     *
     * @throws ApiException
     *
     * @return Issues
     */
    public function getIssuesByVersionAndProject(
        Version $version,
        Project $project,
        IssueStatus $issueStatus = null,
        $offset = 0,
        $limit = 100
    ): Issues;

    /**
     * @param Project $project
     *
     * @throws ApiException
     *
     * @return Versions
     */
    public function getVersionsInProject(Project $project): Versions;
}
