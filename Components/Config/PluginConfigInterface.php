<?php

namespace JodaYellowBox\Components\Config;

interface PluginConfigInterface
{
    /**
     * @param $key
     * @return mixed
     */
    public function get($key);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value);

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
