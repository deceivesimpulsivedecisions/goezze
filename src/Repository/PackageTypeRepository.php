<?php

namespace App\Repository;

use App\Entity\PackageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackageType>
 *
 * @method PackageType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackageType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackageType[]    findAll()
 * @method PackageType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackageType::class);
    }

//    /**
//     * @return PackageType[] Returns an array of PackageType objects
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

//    public function findOneBySomeField($value): ?PackageType
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
