<?php

namespace JodaYellowBox\Components\StateMachine\Factory;

use Shopware\Components\ContainerAwareEventManager;
use SM\Callback\CallbackFactoryInterface;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class Factory extends \SM\Factory\Factory
{
    public function __construct(
        $configs,
        ContainerAwareEventManager $dispatcher = null,
        CallbackFactoryInterface $callbackFactory = null
    ) {
        parent::__construct($configs, null, $callbackFactory);

        $this->dispatcher = $dispatcher;
    }
}
