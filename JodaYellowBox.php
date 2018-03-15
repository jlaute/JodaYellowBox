<?php

declare(strict_types=1);

namespace JodaYellowBox;

use JodaYellowBox\Setup\Installer;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class JodaYellowBox extends Plugin
{
    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('joda_yellow_box.plugin_dir', $this->getPath());

        $config = include $this->getPath() . '/Resources/StateMachine/config.php';
        $container->setParameter('joda_yellow_box.sm.configs', [$config]);

        parent::build($container);
    }

    public function install(Plugin\Context\InstallContext $context)
    {
        $installer = new Installer($this->container);
        $installer->install($context);
    }

    public function uninstall(UninstallContext $context)
    {
        $installer = new Installer($this->container);
        $installer->uninstall($context);
    }
}
