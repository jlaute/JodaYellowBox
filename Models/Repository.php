<?php

declare(strict_types=1);

namespace JodaYellowBox\Models;

use Shopware\Components\Model\ModelRepository;

/**
 * @author    Jörg Lautenschlager <joerg.lautenschlager@gmail.com>
 */
class Repository extends ModelRepository
{
    /**
     * @param string $name
     * @return bool
     */
    public function existsTicket(string $name): bool
    {
        return $this->getTicketByName($name) !== null;
    }

    /**
     * @param string $name
     * @return null|object
     */
    public function getTicketByName(string $name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @return array
     */
    public function getCurrentTickets(): array
    {
        return $this
            ->getEntityManager()
            ->getConnection()
            ->createQueryBuilder()
            ->select('*')
            ->from('s_plugin_yellow_box_ticket')
            ->execute()
            ->fetchAll(\PDO::FETCH_ASSOC);
    }
}
