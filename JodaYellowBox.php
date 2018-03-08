<?php

namespace JodaYellowBox;

use Shopware\Components\Plugin;
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
}
