<?php declare(strict_types=1);
/**
 * © isento eCommerce solutions GmbH
 */

namespace JodaYellowBox\Components\NotificationCenter;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@isento-ecommerce.de>
 * @copyright 2018 isento eCommerce solutions GmbH (http://www.isento-ecommerce.de)
 */
class NotificationCenterCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('joda_yellow_box.notification.registry')) {
            return;
        }

        $services = $container->findTaggedServiceIds('joda_yellow_box.notification');
        $registryDefinition = $container->getDefinition('joda_yellow_box.notification.registry');

        foreach ($services as $serviceId => $tag) {
            $registryDefinition->addMethodCall('add', [$container->getDefinition($serviceId), $serviceId]);
        }
    }
}
