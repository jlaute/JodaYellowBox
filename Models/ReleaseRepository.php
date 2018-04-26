<?php declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Shopware\Components\Model\ModelManager;

/**
 * This repository is not a normal doctrine repository. It is registered as a service, to enable multiple benefits.
 * See https://www.tomasvotruba.cz/blog/2017/10/16/how-to-use-repository-with-doctrine-as-service-in-symfony/
 * for more information
 */
class ReleaseRepository
{
    /**
     * @var ModelManager
     */
    private $repository;

    public function __construct(ModelManager $em)
    {
        $this->repository = $em->getRepository(Release::class);
    }

    /**
     * @return Release|null
     */
    public function findLatestRelease()
    {
        $qb = $this->repository->createQueryBuilder('release');
        $query = $qb->select('release', 'tickets')
            ->join('release.tickets', 'tickets')
            ->orderBy('release.releaseDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        return $paginator->getIterator()->current();
    }
}
