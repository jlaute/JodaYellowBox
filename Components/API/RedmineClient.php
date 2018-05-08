<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

use GuzzleHttp\Message\ResponseInterface;
use JodaYellowBox\Components\API\Client\AbstractClient;
use JodaYellowBox\Components\API\Client\ClientInterface;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Projects;
use JodaYellowBox\Components\API\Struct\Versions;

class RedmineClient extends AbstractClient
{
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
    public function getAllIssues(): Issues
    {
        $response = $this->get('/issues.' . ClientInterface::REQUEST_FORMAT);

        return $this->mapIssues($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuesByProject(Project $project): Issues
    {
        $params = [];
        if ($project) {
            $params = ['project_id' => $project->id];
        }

        $response = $this->get('/issues.' . ClientInterface::REQUEST_FORMAT, $params);

        return $this->mapIssues($response);
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

    protected function mapIssues(ResponseInterface $response): Issues
    {
        $issues = new Issues();
        $jsonContent = $response->json();

        foreach ($jsonContent['issues'] as $responseIssue) {
            $issue = new Issue();
            $issue->id = $responseIssue['id'];
            $issue->name = $responseIssue['subject'];
            $issue->description = $responseIssue['description'];
            $issue->author = $responseIssue['author']['name'];
            $issue->status = $responseIssue['status']['name'];

            $issues->add($issue);
        }

        return $issues;
    }

    protected function mapProjects(ResponseInterface $response): Projects
    {
        $projects = new Projects();

        return $projects;
    }

    protected function mapVersions(ResponseInterface $response): Versions
    {
        $versions = new Versions();

        return $versions;
    }
}
