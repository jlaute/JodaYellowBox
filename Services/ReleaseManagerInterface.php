<?php declare(strict_types=1);

namespace JodaYellowBox\Services;

use JodaYellowBox\Models\Release;

interface ReleaseManagerInterface
{
    /**
     * @return Release|null
     */
    public function getCurrentRelease();
}
