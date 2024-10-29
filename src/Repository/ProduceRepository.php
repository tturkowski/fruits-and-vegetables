<?php

namespace App\Repository;

use App\Entity\Produce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produce>
 */
class ProduceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produce::class);
    }

    //    /**
    //     * @return Produce[] Returns an array of Produce objects
    //     */
    public function findByFilters(array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        // Applying filters dynamically
        if (isset($filters['name'])) {
            $queryBuilder->andWhere('p.name LIKE :name')
                         ->setParameter('name', '%' . $filters['name'] . '%');
        }
        if (isset($filters['weight'])) {
            $queryBuilder->andWhere('p.weight = :weight')
                         ->setParameter('weight', $filters['weight']);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    //    public function findOneBySomeField($value): ?Produce
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
