<?php declare(strict_types=1);
/**
 * © isento eCommerce solutions GmbH
 */

namespace JodaYellowBox\Components\API;

use GuzzleHttp\ClientInterface as GuzzleClientInterface;
use GuzzleHttp\Message\ResponseInterface;
use JodaYellowBox\Components\API\Client\AbstractClient;
use JodaYellowBox\Components\API\Struct\Issue;
use JodaYellowBox\Components\API\Struct\Issues;
use JodaYellowBox\Components\API\Struct\IssueStatus;
use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\API\Struct\Version;
use JodaYellowBox\Components\API\Struct\Versions;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@isento-ecommerce.de>
 * @copyright 2018 isento eCommerce solutions GmbH (http://www.isento-ecommerce.de)
 */
class JiraClient extends AbstractClient
{
    /** @var Issues */
    protected $issues;

    /** @var string */
    protected $passwd;

    /**
     * @param GuzzleClientInterface $client
     * @param string                $apiKey
     * @param string                $passwd
     * @param string                $url
     */
    public function __construct(GuzzleClientInterface $client, string $apiKey, string $passwd, string $url = '')
    {
        $this->passwd = $passwd;
        parent::__construct($client, $apiKey, $url);

        $this->issues = new Issues();
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuesByProject(Project $project,
                                       IssueStatus $issueStatus = null,
                                       $offset = 0,
                                       $limit = 100): Issues
    {
        $defaultQuery = [
            'jql' => 'project=' . $project->id,
        ];

        return $this->getIssues($defaultQuery, $issueStatus, $offset, $limit);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssuesByVersionAndProject(Version $version,
                                                 Project $project,
                                                 IssueStatus $issueStatus = null,
                                                 $offset = 0,
                                                 $limit = 100): Issues
    {
        $defaultQuery = [
            'jql' => 'project=' . $project->id . ' AND fixVersion in unreleasedVersions(' . $version->id . ')',
        ];

        return $this->getIssues($defaultQuery, $issueStatus, $offset, $limit);
    }

    public function getVersionsInProject(Project $project): Versions
    {
        $response = $this->get('project/' . $project->id . '/versions');

        return $this->mapVersions($response);
    }

    /**
     * {@inheritdoc}
     */
    protected function setHttpHeader()
    {
        $this->header = [
            'Authorization' => 'Basic ' . base64_encode($this->apiKey . ':' . $this->passwd),
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
            'maxResults' => $limit,
            'startAt' => $offset,
        ];
        $query = array_merge($query, $defaultQuery);

        $status = [];
        if ($issueStatus) {
            $status = ['status' => $issueStatus->id];
        }

        $params = $this->buildParams($query, $status);

        $response = $this->get('search', $params);
        $jsonContent = $response->json();
        if ((int) $jsonContent['total'] > (int) $jsonContent['maxResults'] + (int) $jsonContent['startAt']) {
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
        if ($query !== [] && $defaultQuery['jql']) {
            foreach ($query as $key => $item) {
                $defaultQuery['jql'] = $defaultQuery['jql'] . ' AND ' . $key . ' in (' . $item . ')';
            }
        }
        unset($query['status']);
        $query = array_merge($defaultQuery, $query);

        return [
            'query' => $query,
        ];
    }

    /**
     * @param ResponseInterface $response
     *
     * @return Issues
     */
    protected function mapIssues(ResponseInterface $response): Issues
    {
        $issues = new Issues();
        $jsonContent = $response->json();

        foreach ($jsonContent['issues'] as $responseIssue) {
            $issue = new Issue();
            $issue->id = (string) $responseIssue['key'];
            $issue->name = $responseIssue['fields']['summary'];
            $issue->number = $responseIssue['key'];
            $issue->description = $responseIssue['fields']['description'];
            $issue->author = $responseIssue['fields']['creator']['displayName'];
            $issue->status = (string) $responseIssue['fields']['status']['name'];

            $issues->add($issue);
        }

        return $issues;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return Versions
     */
    protected function mapVersions(ResponseInterface $response): Versions
    {
        $versions = new Versions();
        $jsonContent = $response->json();

        foreach ($jsonContent['versions'] as $responseVersion) {
            $version = new Version();
            $version->id = $responseVersion['id'];
            $version->name = $responseVersion['name'];
            $version->description = $responseVersion['description'];

            $datetime = new \DateTime();
            $datetime->setTimestamp(strtotime($responseVersion['releaseDate']));
            $version->date = $datetime;

            $versions->add($version);
        }

        return $versions;
    }
}
