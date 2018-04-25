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
     * @param mixed $ident
     *
     * @return bool
     */
    public function existsTicket($ident): bool
    {
        if (\is_int($ident)) {
            $ticket = $this->getTicketById($ident);
        } else {
            $ticket = $this->getTicketByName($ident);
        }

        return $ticket !== null;
    }

    /**
     * Finds a ticket by any ident
     *
     * @param mixed $ident
     *
     * @return null|object
     */
    public function findTicket($ident)
    {
        if (\is_int($ident)) {
            return $this->getTicketById($ident);
        }

        return $this->getTicketByName($ident);
    }

    /**
     * @param int $id
     *
     * @return null|object
     */
    public function getTicketById(int $id)
    {
        return $this->findOneBy(['id' => $id]);
    }

    /**
     * @param mixed $name
     *
     * @return null|object
     */
    public function getTicketByName($name)
    {
        return $this->findOneBy(['name' => $name]);
    }

    /**
     * @return array
     */
    public function getCurrentTickets(): array
    {
        return $this->findAll();
    }
}
