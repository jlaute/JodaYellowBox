<?php

declare(strict_types=1);

namespace JodaYellowBox\DataFixtures;

use Doctrine\ORM\EntityManagerInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;

class FixturesLoader
{
    /**
     * @var bool
     */
    protected $deleteAll = false;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function run()
    {
        if ($this->deleteAll) {
            $this->deleteCurrentData();
        }

        // Loads releases
        $releaseFixture = new ReleaseFixture($this->entityManager);
        $releaseFixture->load();

        // Loads tickets
        $ticketFixture = new TicketFixture($this->entityManager, $releaseFixture);
        $ticketFixture->load();
    }

    /**
     * @param bool $deleteAll
     */
    public function setDeleteAll(bool $deleteAll)
    {
        $this->deleteAll = $deleteAll;
    }

    public function deleteCurrentData()
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->delete(Ticket::class)->getQuery()->execute();
        $qb->delete(Release::class)->getQuery()->execute();
    }
}
