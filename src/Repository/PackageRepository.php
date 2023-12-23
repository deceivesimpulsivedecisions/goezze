<?php

namespace App\Repository;

use App\Entity\Package;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Package>
 *
 * @method Package|null find($id, $lockMode = null, $lockVersion = null)
 * @method Package|null findOneBy(array $criteria, array $orderBy = null)
 * @method Package[]    findAll()
 * @method Package[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Package::class);
    }

    private function __createQueryBuilder()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->leftJoin('p.destination', 'd')
            ;
    }

    private function __applyFilter($qb, $filters = [])
    {
        $orConditions = [];

        foreach($filters as $key => $filter)
        {

            if(!empty($filter) || $filter == '0' ){
                switch($key)
                {
                    case 'category':
                        $categories = explode(',', $filter);
                        $categoryConditions = [];
                        foreach ($categories as $name) {
                            $categoryConditions[] = $qb->expr()->eq('c.name', ':category_' . $name);
                            $qb = $qb->setParameter('category_' . $name, $name);
                        }
                        $orConditions[] = call_user_func_array([$qb->expr(), 'orX'], $categoryConditions);
                        break;
                    case 'destination':
                        $destinations = explode(',', $filter);
                        $destinationConditions = [];
                        foreach ($destinations as $name) {
                            $destinationConditions[] = $qb->expr()->eq('d.name', ':destination_' . $name);
                            $qb = $qb->setParameter('destination_' . $name, $name);
                        }
                        $orConditions[] = call_user_func_array([$qb->expr(), 'orX'], $destinationConditions);
                        break;
                    case 'search':
                        $qb = $qb->orWhere('p.title LIKE :searchTerm')->setParameter('searchTerm', '%' . $filter . '%');
                        break;
                }
            }
        }

        if (!empty($orConditions)) {
            $qb = $qb->andWhere(call_user_func_array([$qb->expr(), 'orX'], $orConditions));
        }


        return $qb;
    }

    public function search($filters, $page = 1, $pageSize = 50)
    {
        $qb = $this->__createQueryBuilder();

        $qb = $this->__applyFilter($qb, $filters);

        $qb = $qb
            ->andWhere('p.isActive = :active')
            ->setParameter('active', true)
            ->setFirstResult($pageSize * ($page-1))
            ->setMaxResults($pageSize)
        ;

        $paginator = new Paginator($qb, $fetchJoinCollection = true);


        $result = [];

        foreach($paginator as $post)
        {
            $result[] = $post;
        }

        return $result;
    }

//    /**
//     * @return Package[] Returns an array of Package objects
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

//    public function findOneBySomeField($value): ?Package
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
