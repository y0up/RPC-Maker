<?php

namespace App\Repository;

use App\Entity\Testimony;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Testimony|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testimony|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testimony[]    findAll()
 * @method Testimony[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimony::class);
    }

    /**
     * Get random testimonies
     */
    public function findRandoms()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('RAND()')
            ->setMaxResults(10) // On LIMIT à 1 résultat
            ->where('m.score >= 4')
            ->getQuery()
            ->getResult(); // On en veut un seul (pas de tableau)
    }

    // /**
    //  * @return Testimony[] Returns an array of Testimony objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Testimony
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
