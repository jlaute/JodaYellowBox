<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use JodaYellowBox\Components\API\Client\AbstractClient;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\IssueStatuses;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Projects;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;

class RedmineClient extends AbstractClient
{
    /** @var array */
    protected $versions = [];

    /** @var array */
    protected $issues;

    public function __construct(GuzzleClientInterface $client, string $apiKey, string $url = '')
    {
        parent::__construct($client, $apiKey, $url);

        $this->issues = new Issues();
    }

    /**
     * {@inheritdoc}
     */
    public function getProjects(): Projects
    {
        $response = $this->get('/projects.' . ClientInterface::REQUEST_FORMAT);

        return $this->mapProjects($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIssueStatuses(): IssueStatuses
    {
        $response = $this->get('/issue_statuses.' . ClientInterface::REQUEST_FORMAT);

        return $this->mapIssueStatuses($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIssues(): Issues
    {
        return $this->getIssues([]);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuesByProject(
        Project $project,
        IssueStatus $issueStatus = null,
        $offset = 0,
        $limit = 100
    ): Issues {
        $defaultQuery = [
            'project_id' => $project->id,
        ];

        return $this->getIssues($defaultQuery, $issueStatus, $offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuesByVersion(
        Version $version,
        IssueStatus $issueStatus = null,
        $offset = 0,
        $limit = 100
    ): Issues {
        $defaultQuery = [
            'fixed_version_id' => $version->id,
        ];

        return $this->getIssues($defaultQuery, $issueStatus, $offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function getVersionsInProject(Project $project): Versions
    {
        $response = $this->get('/projects/' . $project->id . '/versions.' . ClientInterface::REQUEST_FORMAT);

        return $this->mapVersions($response);
    }

    /**
     * {@inheritdoc}
     */
    protected function setHttpHeader()
    {
        $this->header = [
            'X-Redmine-API-Key' => $this->apiKey,
        ];
    }

    /**
     * @param array            $query
     * @param IssueStatus|null $issueStatus
     * @param int              $offset
     * @param int              $limit
     *
     * @throws ApiException
     *
     * @return Issues
     */
    protected function getIssues(
        array $query,
        IssueStatus $issueStatus = null,
        $offset = 0,
        $limit = 100
    ): Issues {
        $defaultQuery = [
            'limit' => $limit,
            'offset' => $offset,
        ];
        $query = array_merge($defaultQuery, $query);

        $status = ['status_id' => '*'];
        if ($issueStatus) {
            $status = ['status_id' => $issueStatus->id];
        }

        $params = $this->buildParams($defaultQuery, $status);

        $response = $this->get('/issues.' . ClientInterface::REQUEST_FORMAT, $params);
        $jsonContent = $response->json();
        if ((int) $jsonContent['total_count'] > (int) $jsonContent['limit'] + (int) $jsonContent['offset']) {
            $this->getIssues($query, $issueStatus, $offset + $limit, $limit);
        }

        $this->issues = $this->issues->mergeIssues($this->mapIssues($response));

        return $this->issues;
    }

    /**
     * @param array $defaultQuery
     * @param array $query
     *
     * @return array
     */
    protected function buildParams(array $defaultQuery = [], array $query = []): array
    {
        $query = array_merge($defaultQuery, $query);

        return [
            'query' => $query,
        ];
    }

    protected function mapIssues(ResponseInterface $response): Issues
    {
        $issues = new Issues();
        $jsonContent = $response->json();

        foreach ($jsonContent['issues'] as $responseIssue) {
            $issue = new Issue();
            $issue->id = (string) $responseIssue['id'];
            $issue->name = $responseIssue['subject'];
            $issue->description = $responseIssue['description'];
            $issue->author = $responseIssue['author']['name'];
            $issue->status = (string) $responseIssue['status']['id'];

            $issues->add($issue);
        }

        return $issues;
    }

    protected function mapProjects(ResponseInterface $response): Projects
    {
        $projects = new Projects();
        $jsonContent = $response->json();

        foreach ($jsonContent['projects'] as $jsonProject) {
            $project = new Project();
            $project->id = (string) $jsonProject['id'];
            $project->name = $jsonProject['name'];
            $project->description = $jsonProject['description'];
            $project->created = $jsonProject['created_on'];

            $projects->add($project);
        }

        return $projects;
    }

    protected function mapVersions(ResponseInterface $response): Versions
    {
        $versions = new Versions();
        $jsonContent = $response->json();

        foreach ($jsonContent['versions'] as $jsonVersion) {
            $version = new Version();
            $version->id = (string) $jsonVersion['id'];
            $version->name = $jsonVersion['name'];
            $version->description = $jsonVersion['description'];

            $datetime = new \DateTime();
            $datetime->setTimestamp(strtotime($jsonVersion['created_on']));
            $version->date = $datetime;

            $versions->add($version);
        }

        return $versions;
    }

    protected function mapIssueStatuses(ResponseInterface $response): IssueStatuses
    {
        $issueStatuses = new IssueStatuses();
        $jsonContent = $response->json();

        foreach ($jsonContent['issue_statuses'] as $jsonIssueStatus) {
            $issueStatus = new IssueStatus();
            $issueStatus->id = (string) $jsonIssueStatus['id'];
            $issueStatus->name = $jsonIssueStatus['name'];

            $issueStatuses->add($issueStatus);
        }

        return $issueStatuses;
    }
}
