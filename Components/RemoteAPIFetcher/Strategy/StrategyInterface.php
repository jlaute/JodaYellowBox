<?php declare(strict_types=1);

namespace JodaYellowBox\Components\RemoteAPIFetcher\Strategy;

use JodaYellowBox\Components\API\Struct\Project;

interface StrategyInterface
{
    public function fetchData(Project $project);
}
