<?php
/**
 * Article Repository.
 */

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Article repository class.
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    /**
     * ArticleRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('t.updatedAt', 'DESC');
    }

    /**
     * Query only not accepted users.
     *
     * @return QueryBuilder
     */
    public function queryNotAccepted(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->where('t.isAccepted = 0');
    }

    /**
     * Queries accepted articles.
     *
     * @return QueryBuilder
     */
    public function queryAccepted(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->where('t.isAccepted = 1')
            ->orderBy('t.updatedAt', 'DESC');
    }

    /**
     * Queries records by user defined filter.
     *
     * @param Request $request
     *
     * @return QueryBuilder
     */
    public function queryFilter(Request $request): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->where('t.title LIKE :searchPhrase')
            ->setParameter('searchPhrase', '%'.$request->query->getAlnum('filter').'%')
            ->orderBy('t.title', 'ASC');
    }

    /**
     * Saves record to database.
     *
     * @param Article $article
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Article $article): void
    {
        $this->_em->persist($article);
        $this->_em->flush($article);
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder Query builder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder
    {
        return $queryBuilder ?: $this->createQueryBuilder('t');
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
