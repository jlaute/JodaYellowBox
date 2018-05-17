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
        $this->fixtureData[] = new Ticket('Der Footer des Shops soll 3 Spaltig sein', 'YB-001');
        $this->fixtureData[] = new Ticket('Auf der Startseite soll ein Banner mit einem Haus eingefügt werden', 'YB-002');
        $this->fixtureData[] = new Ticket('In der Shop-Navigation soll der Blog eingebunden werden', 'YB-003');
        $this->fixtureData[] = new Ticket('In der Suche soll der Platzhalter `Suchbegriff...` stehen', 'YB-004');
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
