<?php

namespace JodaYellowBox\Components\StateMachine\Callback;

use SM\Callback\CallbackFactory;
use SM\SMException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class ContainerAwareCallbackFactory extends CallbackFactory
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct($class, ContainerInterface $container)
    {
        parent::__construct($class);

        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     * @throws SMException
     */
    public function get(array $specs)
    {
        if (!isset($specs['do'])) {
            throw new SMException(sprintf(
                'CallbackFactory::get needs the index "do" to be able to build a callback, array %s given.',
                json_encode($specs)
            ));
        }
        $class = $this->class;
        return new $class($specs, $specs['do'], $this->container);
    }
}
