<?php

namespace App\Repository;

use App\Entity\Fruit;
use App\Entity\Produce;
use App\Entity\Vegetable;
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
    public function findByFiltered(array $filters, $type = Fruit::class): array
    {
        $queryBuilder = $this->createQueryBuilder('p');

        $queryBuilder->andWhere("p INSTANCE OF ".$type);

        if (isset($filters['name'])) {
            $queryBuilder->andWhere('p.name LIKE :name')
                         ->setParameter('name', '%' . $filters['name'] . '%');
        }
        if (isset($filters['weight'])) {
            $queryBuilder->andWhere('p.weight = :weight')
                         ->setParameter('weight', $filters['weight']);
        }

        if (isset($filters['units']) && $filters['units'] === 'kg') {
            $queryBuilder->andWhere('p.weight = :weightInGrams')
                         ->setParameter('weightInGrams', $filters['weight'] / 1000);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
