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

    /**
     * {@inheritdoc}
     */
    public function getReleaseToDisplay(): string
    {
        return (string) $this->get('JodaYellowBoxReleaseToDisplay');
    }

    /**
     * {@inheritdoc}
     */
    public function getApiUrl(): string
    {
        return (string) $this->get('JodaYellowBoxApiUrl');
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey(): string
    {
        return (string) $this->get('JodaYellowBoxApiKey');
    }

    /**
     * @return string
     */
    public function getManagementToolName(): string
    {
        return (string) $this->get('JodaYellowBoxManagementToolName');
    }
}
