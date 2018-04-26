<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;

class Controllers implements SubscriberInterface
{
    /**
     * @var string
     */
    private $pluginDir;

    /**
     * @param string $pluginDir
     */
    public function __construct(string $pluginDir)
    {
        $this->pluginDir = $pluginDir;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Dispatcher_ControllerPath_Frontend_YellowBox' => 'onFrontendYellowBox',
        ];
    }

    /**
     * @return string
     */
    public function onFrontendYellowBox()
    {
        return $this->pluginDir . '/Controllers/Frontend/YellowBox.php';
    }
}