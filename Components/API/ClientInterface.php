<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

interface ClientInterface
{
    const REQUEST_FORMAT = 'json';

    /**
     * get all issues or just project specific ones
     *
     * @param int $projectId
     *
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function getIssues(int $projectId = 0);

    /**
     * @return \GuzzleHttp\Message\ResponseInterface
     */
    public function getProjects();
}
