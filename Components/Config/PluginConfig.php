<?php declare(strict_types=1);

namespace JodaYellowBox\Components\Config;

use Shopware\Components\Plugin\ConfigReader;

class PluginConfig implements PluginConfigInterface
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param string       $pluginName
     * @param ConfigReader $configReader
     */
    public function __construct(string $pluginName, ConfigReader $configReader)
    {
        $this->config = $configReader->getByPluginName($pluginName);
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, $value)
    {
        $this->config[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        return $this->config[$key];
    }

    public function getConfig(): array
    {
        return $this->config;
    }

    public function getLessConfiguration(): array
    {
        return [
            'JodaYellowBoxMaxWidth' => $this->get('JodaYellowBoxMaxWidth'),
        ];
    }
}
