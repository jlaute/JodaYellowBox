<?php

namespace JodaYellowBox;

use JodaYellowBox\Setup\Installer;
use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\UninstallContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class JodaYellowBox extends Plugin
{
    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('joda_yellow_box.plugin_dir', $this->getPath());
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
