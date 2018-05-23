<?php declare(strict_types=1);

namespace JodaYellowBox\Models;

use Doctrine\ORM\EntityRepository;
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
    private $modelManager;

    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(ModelManager $em)
    {
        $this->modelManager = $em;
        $this->repository = $em->getRepository(Release::class);
    }

    /**
     * @return array|Release[]
     */
    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    /**
     * @param array $externalIds
     *
     * @return array|Release[]
     */
    public function findByExternalIds(array $externalIds): array
    {
        $qb = $this->repository->createQueryBuilder('release')
            ->select('release');
        $query = $qb->where($qb->expr()->in('release.externalId', $externalIds))
            ->getQuery();

        return $query->getResult();
    }

    /**
     * @return Release|null
     */
    public function findLatestRelease()
    {
        $qb = $this->createBasicSingleResultQueryBuilder();
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
        $qb = $this->createBasicSingleResultQueryBuilder();
        $query = $qb->where($qb->expr()->eq('release.name', ':name'))
            ->setParameter('name', $name)
            ->getQuery();

        $paginator = $this->createPaginator($query);

        return $paginator->getIterator()->current();
    }

    /**
     * @param Release $release
     */
    public function add(Release $release)
    {
        $this->modelManager->persist($release);
    }

    public function save()
    {
        $this->modelManager->flush();
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function createBasicSingleResultQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->repository->createQueryBuilder('release')
            ->select('release', 'tickets')
            ->leftJoin('release.tickets', 'tickets')
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
