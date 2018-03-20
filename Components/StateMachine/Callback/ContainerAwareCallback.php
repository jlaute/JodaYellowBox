<?php

declare(strict_types=1);

namespace JodaYellowBox\Components\StateMachine\Callback;

use SM\Callback\Callback;
use SM\Event\TransitionEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class ContainerAwareCallback extends Callback
{
    protected $container;

    public function __construct(array $specs, $callable, ContainerInterface $container)
    {
        parent::__construct($specs, $callable);

        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function call(TransitionEvent $event)
    {
        // Load the services only now (when the callback is actually called)
        if (
            \is_array($this->callable)
            && \is_string($this->callable[0])
            && \strpos($this->callable[0], '@') === 0
        ) {
            $serviceId = \substr($this->callable[0], 1);
            $this->callable[0] = $this->container->get($serviceId);
        }

        return parent::call($event);
    }
}
