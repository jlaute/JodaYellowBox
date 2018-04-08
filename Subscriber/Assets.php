<?php declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
use JodaYellowBox\Components\Config\PluginConfigInterface;
use Shopware\Components\Theme\LessDefinition;

class Assets implements SubscriberInterface
{
    /**
     * @var array
     */
    protected $lessConfig;
    /**
     * @var string
     */
    private $lessDir;

    /**
     * @var string
     */
    private $jsDir;

    /**
     * @param string                $lessDir
     * @param string                $jsDir
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

    public function onCollectLess()
    {
        return new LessDefinition($this->lessConfig, [
            $this->lessDir . '/all.less',
        ]);
    }

    public function onCollectJavascript()
    {
        return new ArrayCollection([
            $this->jsDir . '/jquery.yellow-box.js',
        ]);
    }
}
