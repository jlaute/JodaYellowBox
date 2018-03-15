<?php

namespace JodaYellowBox\Setup;

use Doctrine\ORM\Tools\SchemaTool;
use JodaYellowBox\Models\Ticket;
use Shopware\Components\DependencyInjection\Container;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UninstallContext;

/**
 * @author Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class Installer
{
    private $em;

    private $schemaTool;

    public function __construct(Container $container)
    {
        $this->em = $container->get('models');
        $this->schemaTool = new SchemaTool($this->em);
    }

    public function install(InstallContext $installContext)
    {
        $this->createDatabase();
    }

    public function uninstall(UninstallContext $uninstallContext)
    {
        if (!$uninstallContext->keepUserData()) {
            $this->removeDatabase();
        }
    }

    protected function createDatabase()
    {
        $this->schemaTool->updateSchema($this->getClassesMetadata(), true);
    }

    protected function removeDatabase()
    {
        $this->schemaTool->dropSchema($this->getClassesMetadata());
    }

    protected function getClassesMetadata()
    {
        return [
            $this->em->getClassMetadata(Ticket::class)
        ];
    }
}
