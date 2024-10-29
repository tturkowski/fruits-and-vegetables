<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fruit>
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function findByFiltered(array $filters): array
    {
        $queryBuilder = $this->createQueryBuilder('f');

        if (isset($filters['name'])) {
            $queryBuilder->andWhere('f.name LIKE :name')
                         ->setParameter('name', '%' . $filters['name'] . '%');
        }

        if (isset($filters['weight'])) {
            $queryBuilder->andWhere('f.weight = :weight')
                         ->setParameter('weight', $filters['weight']);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
