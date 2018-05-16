<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API\Client;

use JodaYellowBox\Components\API\Struct\Issues;
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
     * @return Issues
     */
    public function getAllIssues(): Issues;

    /**
     * get all project specific issues
     *
     * @param Project $project
     *
     * @return Issues
     */
    public function getIssuesByProject(Project $project): Issues;

    /**
     * @param Version $version
     *
     * @return Issues
     */
    public function getIssuesByVersion(Version $version): Issues;

    /**
     * @return Projects
     */
    public function getProjects(): Projects;

    /**
     * @param Project $project
     *
     * @return Versions
     */
    public function getVersionsInProject(Project $project): Versions;
}
