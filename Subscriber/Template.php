<?php

declare(strict_types=1);

namespace JodaYellowBox\Subscriber;

use Enlight\Event\SubscriberInterface;

class Template implements SubscriberInterface
{
    /**
     * @var string
     */
    private $viewDir;

    /**
     * @param string $viewDir
     */
    public function __construct(string $viewDir)
    {
        $this->viewDir = $viewDir;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'Theme_Inheritance_Template_Directories_Collected' => 'onCollectedTemplates',
        ];
    }

    /**
     * @param \Enlight_Event_EventArgs $args
     */
    public function onCollectedTemplates(\Enlight_Event_EventArgs $args)
    {
        $directories = $args->getReturn();
        $directories[] = $this->viewDir;
        $args->setReturn($directories);
    }
}
