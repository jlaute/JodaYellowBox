<?php

namespace JodaYellowBox\Components\Config;

use Shopware\Components\Plugin\ConfigReader;
use Doctrine\Common\Collections\ArrayCollection;

class PluginConfig extends ArrayCollection implements PluginConfigInterface
{
    /**
     * @param string $pluginName
     * @param ConfigReader $configReader
     */
    public function __construct(string $pluginName, ConfigReader $configReader)
    {
        $elements = $configReader->getByPluginName($pluginName);
        parent::__construct($elements);
    }

    /**
     * Gets the whole config
     * @return array
     */
    public function getConfig(): array
    {
        return $this->toArray();
    }

    /**
     * Gets the config for less integration
     * @return array
     */
    public function getLessConfiguration(): array
    {
        return [
            'JodaYellowBoxMaxWidth' => $this->get('JodaYellowBoxMaxWidth'),
        ];
    }
}
