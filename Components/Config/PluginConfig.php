<?php declare(strict_types=1);

namespace JodaYellowBox\Components\Config;

use Doctrine\Common\Collections\ArrayCollection;
use Shopware\Components\Plugin\ConfigReader;

class PluginConfig extends ArrayCollection implements PluginConfigInterface
{
    /**
     * @param string       $pluginName
     * @param ConfigReader $configReader
     */
    public function __construct(string $pluginName, ConfigReader $configReader)
    {
        $elements = $configReader->getByPluginName($pluginName);
        parent::__construct($elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfig(): array
    {
        return $this->toArray();
    }

    /**
     * {@inheritdoc}
     */
    public function getLessConfiguration(): array
    {
        return [
            'JodaYellowBoxMaxWidth' => $this->get('JodaYellowBoxMaxWidth'),
        ];
    }
}
