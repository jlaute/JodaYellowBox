<?php

use Shopware\Models\Shop\Shop;
use Shopware\Models\Shop\Repository;

require __DIR__ . '/../../../../../autoload.php';

class TestKernel extends \Shopware\Kernel
{
    /**
     * Static method to start boot kernel without leaving local scope in test helper
     */
    public static function start()
    {
        $kernel = new self('testing', true);
        $kernel->boot();

        $container = $kernel->getContainer();
        $container->get('plugins')->Core()->ErrorHandler()->registerErrorHandler(E_ALL | E_STRICT);

        /** @var $repository Repository */
        $repository = $container->get('models')->getRepository(Shop::class);

        $shop = $repository->getActiveDefault();
        $shop->registerResources();

        $_SERVER['HTTP_HOST'] = $shop->getHost();
    }

    protected function getConfigPath()
    {
        return __DIR__ . '/config.php';
    }
}

TestKernel::start();
