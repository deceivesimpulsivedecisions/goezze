<?php

namespace App\Repository;

use App\Entity\PackageEnquiry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackageEnquiry>
 *
 * @method PackageEnquiry|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackageEnquiry|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackageEnquiry[]    findAll()
 * @method PackageEnquiry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageEnquiryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackageEnquiry::class);
    }

//    /**
//     * @return PackageEnquiry[] Returns an array of PackageEnquiry objects
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

//    public function findOneBySomeField($value): ?PackageEnquiry
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
