<?php

declare(strict_types=1);

namespace JodaYellowBox\DataFixtures;

interface FixtureInterface
{
    /**
     * Loads the fixture data
     */
    public function load();

    /**
     * Gets the fixture data
     *
     * @return mixed
     */
    public function getFixtureData();
}
