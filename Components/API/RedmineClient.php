<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

class RedmineClient extends AbstractClient
{
    /**
     * {@inheritdoc}
     */
    public function getProjects()
    {
        return $this->get('/projects.' . ClientInterface::REQUEST_FORMAT);
    }

    /**
     * {@inheritdoc}
     */
    public function getIssues(int $projectId = 0)
    {
        $params = [];
        if ($projectId) {
            $params = ['project_id' => $projectId];
        }

        return $this->get('/issues.' . ClientInterface::REQUEST_FORMAT, $params);
    }

    protected function setHttpHeader()
    {
        $this->header = [
            'X-Redmine-API-Key' => $this->apiKey,
        ];
    }
}
