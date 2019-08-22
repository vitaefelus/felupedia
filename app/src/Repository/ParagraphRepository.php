<?php
/**
 * Paragraph repository.
 */

namespace App\Repository;

use App\Entity\Paragraph;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Paragraph repository class.
 *
 * @method Paragraph|null find($id, $lockMode = null, $lockVersion = null)
 * @method Paragraph|null findOneBy(array $criteria, array $orderBy = null)
 * @method Paragraph[]    findAll()
 * @method Paragraph[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParagraphRepository extends ServiceEntityRepository
{
    /**
     * ParagraphRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Paragraph::class);
    }

    /**
     * Save record.
     *
     * @param Paragraph $paragraph
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Paragraph $paragraph): void
    {
        $this->_em->persist($paragraph);
        $this->_em->flush($paragraph);
    }

    /**
     * Delete record.
     *
     * @param Paragraph $paragraph
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function delete(Paragraph $paragraph): void
    {
        $this->_em->remove($paragraph);
        $this->_em->flush($paragraph);
    }

    // /**
    //  * @return Paragraph[] Returns an array of Paragraph objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Paragraph
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
