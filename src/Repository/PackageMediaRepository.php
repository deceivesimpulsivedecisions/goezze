<?php

namespace App\Repository;

use App\Entity\PackageMedia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PackageMedia>
 *
 * @method PackageMedia|null find($id, $lockMode = null, $lockVersion = null)
 * @method PackageMedia|null findOneBy(array $criteria, array $orderBy = null)
 * @method PackageMedia[]    findAll()
 * @method PackageMedia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageMediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PackageMedia::class);
    }

//    /**
//     * @return PackageMedia[] Returns an array of PackageMedia objects
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

//    public function findOneBySomeField($value): ?PackageMedia
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
