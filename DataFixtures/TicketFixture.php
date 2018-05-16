<?php

declare(strict_types=1);

namespace JodaYellowBox\DataFixtures;

use Doctrine\ORM\EntityManagerInterface;
use JodaYellowBox\Models\Release;
use JodaYellowBox\Models\Ticket;

class TicketFixture extends Fixture
{
    /**
     * @var FixtureInterface
     */
    private $additionalFixture;

    /**
     * @param EntityManagerInterface $entityManager
     * @param FixtureInterface|null  $additionalFixture
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        FixtureInterface $additionalFixture = null
    ) {
        parent::__construct($entityManager);

        $this->additionalFixture = $additionalFixture;
    }

    public function create()
    {
        $this->fixtureData[] = new Ticket('Ticket 1', 'JODA-001', 'Ich bin eine Test Beschreibung');
        $this->fixtureData[] = new Ticket('Ticket 2', 'JODA-002', 'Ich bin eine Test Beschreibung');
        $this->fixtureData[] = new Ticket('Ticket 3', 'JODA-003', 'Ich bin eine Test Beschreibung');
    }

    public function load()
    {
        parent::load();

        if ($this->additionalFixture === null) {
            return;
        }

        /** @var Release $release */
        foreach ($this->additionalFixture->getFixtureData() as $release) {
            $release->addTickets($this->fixtureData);
            $this->entityManager->persist($release);
        }

        $this->entityManager->flush();
    }
}
