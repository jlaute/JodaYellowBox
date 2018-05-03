<?php

declare(strict_types=1);

namespace JodaYellowBox\Setup;

use Doctrine\ORM\Tools\SchemaTool;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;
use JodaYellowBox\Models\TicketHistory;
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
        $this->clearCache($installContext);
    }

    public function uninstall(UninstallContext $uninstallContext)
    {
        if (!$uninstallContext->keepUserData()) {
            $this->removeDatabase();
        }

        $this->clearCache($uninstallContext);
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
            $this->em->getClassMetadata(Ticket::class),
            $this->em->getClassMetadata(TicketHistory::class),
            $this->em->getClassMetadata(Release::class),
        ];
    }

    protected function clearCache(InstallContext $installContext)
    {
        $installContext->scheduleClearCache(InstallContext::CACHE_LIST_FRONTEND);
    }
}
