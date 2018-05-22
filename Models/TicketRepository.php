<?php

declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\ORM\EntityManager;
use Shopware\Components\Model\ModelRepository;

/**
 * This repository is not a normal doctrine repository. It is registered as a service, to enable multiple benefits.
 * See https://www.tomasvotruba.cz/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/
 * for more information
 */
class TicketRepository
{
    /**
     * @var ModelRepository
     */
    private $repository;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Ticket::class);
        $this->em = $em;
    }

    /**
     * @param $id
     * @param null $lockMode
     * @param null $lockVersion
     *
     * @return Ticket|null
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->repository->find($id, $lockMode, $lockVersion);
    }

    /**
     * @return array|Ticket[]
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

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
     * @return Ticket|null
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
     * @return Ticket|null
     */
    public function getTicketById(int $id)
    {
        return $this->repository->findOneBy(['id' => $id]);
    }

    /**
     * @param mixed $name
     *
     * @return Ticket|null
     */
    public function getTicketByName($name)
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    /**
     * @return array
     */
    public function getCurrentTickets(): array
    {
        $qb = $this->repository->createQueryBuilder('ticket');

        $qb->where(
            $qb->expr()->in('ticket.state', [Ticket::STATE_OPEN, Ticket::STATE_REOPENED])
        );

        return $qb->getQuery()->getResult();
    }

    public function add(Ticket $ticket)
    {
        $this->em->persist($ticket);
    }

    public function save()
    {
        $this->em->flush();
    }

    /**
     * @param Ticket $ticket
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Ticket $ticket)
    {
        $this->em->remove($ticket);
        $this->em->flush($ticket);
    }
}
