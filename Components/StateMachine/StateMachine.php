<?php

namespace JodaYellowBox\Components\StateMachine;
use Shopware\Components\ContainerAwareEventManager;
use SM\Callback\CallbackFactoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class StateMachine extends \SM\StateMachine\StateMachine
{
    /**
     * @var ContainerAwareEventManager
     */
    protected $swDispatcher;

    public function __construct(
        object $object,
        array $config,
        ContainerAwareEventManager $dispatcher = null,
        CallbackFactoryInterface $callbackFactory = null
    ) {
        parent::__construct($object, $config, null, $callbackFactory);

        $this->swDispatcher = $dispatcher;
    }
}
