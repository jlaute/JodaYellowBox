<?php declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\ORM\Query;
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
        $qb = $this->createBasicQueryBuilder();
        $query = $qb->orderBy('release.releaseDate', 'DESC')
            ->getQuery();

        $paginator = $this->createPaginator($query);

        return $paginator->getIterator()->current();
    }

    /**
     * @param string $name
     *
     * @return Release|null
     */
    public function findReleaseByName(string $name)
    {
        $qb = $this->createBasicQueryBuilder();
        $query = $qb->where($qb->expr()->eq('release.name', ':name'))
            ->setParameter('name', $name)
            ->getQuery();

        $paginator = $this->createPaginator($query);

        return $paginator->getIterator()->current();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createBasicQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository->createQueryBuilder('release')
            ->select('release', 'tickets')
            ->join('release.tickets', 'tickets')
            ->setMaxResults(1);
    }

    /**
     * @param Query $query
     *
     * @return Paginator
     */
    protected function createPaginator(Query $query): Paginator
    {
        $paginator = new Paginator($query);
        $paginator->setUseOutputWalkers(false);

        return $paginator;
    }
}
