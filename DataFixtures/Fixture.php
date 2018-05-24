<?php

declare(strict_types=1);

namespace JodaYellowBox\DataFixtures;

use Doctrine\ORM\EntityManagerInterface;

abstract class Fixture implements FixtureInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $fixtureData = [];

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    abstract public function create();

    public function load()
    {
        $this->create();

        foreach ($this->getFixtureData() as $data) {
            $this->entityManager->persist($data);
        }

        $this->entityManager->flush();
    }

    /**
     * @return array
     */
    public function getFixtureData(): array
    {
        return $this->fixtureData;
    }
}
