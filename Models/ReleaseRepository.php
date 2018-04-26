<?php declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\ORM\EntityManager;
use Shopware\Components\Model\ModelRepository;

/**
 * This repository is not a normal doctrine repository. It is registered as a service, to enable multiple benefits.
 * See https://www.tomasvotruba.cz/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/
 * for more information
 */
class ReleaseRepository
{
    /**
     * @var ModelRepository
     */
    private $repository;

    public function __construct(EntityManager $em)
    {
        $this->repository = $em->getRepository(Ticket::class);
    }

    /**
     * @return Release
     */
    public function findLatestRelease(): Release
    {
        $qb = $this->repository->createQueryBuilder('release');
        $query = $qb->orderBy('release.releaseDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
