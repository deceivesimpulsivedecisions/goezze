<?php

namespace App\Repository;

use App\Entity\PackageCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackageCategory>
 *
 * @method PackageCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackageCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackageCategory[]    findAll()
 * @method PackageCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackageCategory::class);
    }

//    /**
//     * @return PackageCategory[] Returns an array of PackageCategory objects
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

//    public function findOneBySomeField($value): ?PackageCategory
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
