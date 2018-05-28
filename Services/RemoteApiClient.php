<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Components\API\Struct\Project;
use JodaYellowBox\Components\RemoteAPIFetcher\Strategy\StrategyInterface;

class RemoteApiClient implements RemoteApiClientInterface
{
    /** @var StrategyInterface */
    protected $strategy;

    protected $project;

    public function __construct(StrategyInterface $strategy, string $externalProjectId = '')
    {
        $this->strategy = $strategy;

        $this->project = new Project();
        $this->project->id = $externalProjectId;
    }

    public function fetchData()
    {
        $this->strategy->fetchData($this->project);
    }
}
