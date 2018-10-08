<?php

declare(strict_types=1);

namespace JodaYellowBox;

use JodaYellowBox\Components\NotificationCenter\NotificationCenterCompilerPass;
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
        $config = include $this->getPath() . '/Resources/StateMachine/config.php';
        $container->setParameter('joda_yellow_box.sm.configs', [$config]);

        $container->addCompilerPass(new NotificationCenterCompilerPass());

        parent::build($container);
    }

    /**
     * @param Plugin\Context\InstallContext $context
     */
    public function install(Plugin\Context\InstallContext $context)
    {
        $installer = new Installer($this->container);
        $installer->install($context);
    }

    /**
     * @param UninstallContext $context
     */
    public function uninstall(UninstallContext $context)
    {
        $installer = new Installer($this->container);
        $installer->uninstall($context);
    }
}
