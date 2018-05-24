<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Client;

use JodaYellowBox\Components\API\ApiException;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\IssueStatuses;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Projects;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;

interface ClientInterface
{
    const REQUEST_FORMAT = 'json';

    /**
     * get all issues
     *
     * @throws ApiException
     *
     * @return Issues
     */
    public function getAllIssues(): Issues;

    /**
     * get all project specific issues
     *
     * @param Project $project
     *
     * @throws ApiException
     *
     * @return Issues
     */
    public function getIssuesByProject(Project $project): Issues;

    /**
     * @param Version     $version
     * @param IssueStatus $issueStatus
     * @param int         $offset
     * @param int         $limit
     *
     * @throws ApiException
     *
     * @return Issues
     */
    public function getIssuesByVersion(
        Version $version,
        IssueStatus $issueStatus = null,
        $offset = 0,
        $limit = 100
    ): Issues;

    /**
     * @throws ApiException
     *
     * @return Projects
     */
    public function getProjects(): Projects;

    /**
     * Returns all statuses that a ticket can have
     *
     * @throws ApiException
     *
     * @return IssueStatuses
     */
    public function getAllIssueStatuses(): IssueStatuses;

    /**
     * @param Project $project
     *
     * @throws ApiException
     *
     * @return Versions
     */
    public function getVersionsInProject(Project $project): Versions;
}
