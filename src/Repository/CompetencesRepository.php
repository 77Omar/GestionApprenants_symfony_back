<?php

namespace App\Repository;

use App\Entity\Competences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Competences|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competences|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competences[]    findAll()
 * @method Competences[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetencesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competences::class);
    }

    // /**
    //  * @return Competences[] Returns an array of Competences objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Competences
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getCbyGbyRef($id1,$id2)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.groupeCompetences', 'g')
            ->innerJoin('g.referentiels', 'r')
            ->andWhere('r.id= :va')
            ->setParameter('va', $id1)
            ->andWhere('g.id= :val')
            ->setParameter('val', $id2)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult();


    }
}
