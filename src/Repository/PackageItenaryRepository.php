<?php

namespace App\Repository;

use App\Entity\PackageItenary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackageItenary>
 *
 * @method PackageItenary|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackageItenary|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackageItenary[]    findAll()
 * @method PackageItenary[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageItenaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackageItenary::class);
    }

//    /**
//     * @return PackageItenary[] Returns an array of PackageItenary objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PackageItenary
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
