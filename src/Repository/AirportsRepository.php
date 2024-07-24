<?php

namespace App\Repository;

use App\Entity\Airports;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Airports>
 *
 * @method Airports|null find($id, $lockMode = null, $lockVersion = null)
 * @method Airports|null findOneBy(array $criteria, array $orderBy = null)
 * @method Airports[]    findAll()
 * @method Airports[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AirportsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Airports::class);
    }

    /**
     * @return Airports[] Returns an array of Airports objects
     */
    public function findAirports($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.airportCode LIKE :val')
            ->orWhere('a.airportName LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orderBy('a.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

//    public function findOneBySomeField($value): ?Airports
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
