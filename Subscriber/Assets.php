<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Enlight\Event\SubscriberInterface;
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
     * @param string $lessDir
     * @param string $jsDir
     */
    public function __construct(string $lessDir, string $jsDir)
    {
        $this->lessDir = $lessDir;
        $this->jsDir = $jsDir;
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
        return new LessDefinition([], [
            $this->lessDir . '/all.less',
        ]);
    }

    /**
     * @return ArrayCollection
     */
    public function onCollectJavascript()
    {
        return new ArrayCollection([
            $this->jsDir . '/jquery.yellow-box.js',
            $this->jsDir . '/jquery-ui.js',
            $this->jsDir . '/jquery.confirmation.js',
        ]);
    }
}
