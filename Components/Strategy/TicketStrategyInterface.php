<?php declare(strict_types=1);

namespace JodaYellowBox\Components\Strategy;

use JodaYellowBox\Components\API\Struct\Project;

interface TicketStrategyInterface
{
    /**
     * Call remote API and fetch external data with local data
     *
     * @param Project $project
     */
    public function fetchData(Project $project);

    /**
     * @return array
     */
    public function getCurrentTickets(): array;

    /**
     * @return string
     */
    public function getCurrentReleaseName(): string;
}
