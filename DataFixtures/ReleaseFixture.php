<?php

declare(strict_types=1);

namespace JodaYellowBox\DataFixtures;

use JodaYellowBox\Models\Release;

class ReleaseFixture extends Fixture
{
    public function create()
    {
        $this->fixtureData[] = new Release('JODA-Release v1.0.0');
    }
}
