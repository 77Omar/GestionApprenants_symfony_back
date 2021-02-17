<?php

namespace App\Repository;

use App\Entity\ProfilSorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilSorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilSorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilSorties[]    findAll()
 * @method ProfilSorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilSortiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilSorties::class);
    }

    // /**
    //  * @return ProfilSorties[] Returns an array of ProfilSorties objects
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
    public function findOneBySomeField($value): ?ProfilSorties
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findByProfilSortie()
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.apprenants', 'a')
            ->orderBy('g.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}
