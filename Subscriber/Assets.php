<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Theme\LessDefinition;
use Doctrine\Common\Collections\ArrayCollection;
use JodaYellowBox\Components\Config\PluginConfigInterface;

class Assets implements SubscriberInterface
{
    /**
     * @var string
     */
    private $lessDir;

    /**
     * @var string
     */
    private $jsDir;

    /**
     * @var array
     */
    protected $lessConfig;

    /**
     * @param string $lessDir
     * @param string $jsDir
     * @param PluginConfigInterface $config
     */
    public function __construct(string $lessDir, string $jsDir, PluginConfigInterface $config)
    {
        $this->lessDir = $lessDir;
        $this->jsDir = $jsDir;

        $this->lessConfig = $config->getLessConfiguration();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Compiler_Collect_Plugin_Less' => 'onCollectLess',
            'Theme_Compiler_Collect_Plugin_Javascript' => 'onCollectJavascript',
        ];
    }

    /**
     * @return LessDefinition
     */
    public function onCollectLess()
    {
        return new LessDefinition($this->lessConfig, [
            $this->lessDir . '/all.less'
        ]);
    }

    /**
     * @return ArrayCollection
     */
    public function onCollectJavascript()
    {
        return new ArrayCollection([
            $this->jsDir . '/jquery.yellow-box.js',
        ]);
    }
}
