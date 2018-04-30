<?php declare(strict_types=1);

namespace JodaYellowBox\Components\API;

interface ClientInterface
{
    const REQUEST_FORMAT = 'json';

    public function getIssues(int $projectId);

    public function getProjects();
}
