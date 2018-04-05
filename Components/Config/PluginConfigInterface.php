<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\Config;

interface PluginConfigInterface
{
    /**
     * Gets the complete config
     * @return array
     */
    public function getConfig(): array;

    /**
     * Gets the config for less files
     * @return array
     */
    public function getLessConfiguration(): array;
}
