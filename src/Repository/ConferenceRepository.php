<?php

namespace App\Repository;

use App\Entity\Conference;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conference>
 */
class ConferenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conference::class);
    }

    public function findBetweenDates(?\DateTimeImmutable $startDate = null, ?\DateTimeImmutable $endDate = null): array
    {
        if ($startDate === null && $endDate === null) {
            throw new \InvalidArgumentException('Start date and end date must not be null');
        }

        $qb = $this->createQueryBuilder('c');

        if ($startDate instanceof \DateTimeImmutable) {
            $qb->andWhere($qb->expr()->gte('c.createdAt', ':startDate'))
                ->setParameter('startDate', $startDate);
        }

        if ($endDate instanceof \DateTimeImmutable) {
            $qb->andWhere($qb->expr()->lte('c.createdAt', ':endDate'))
                ->setParameter('endDate', $endDate);
        }

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Conference[] Returns an array of Conference objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Conference
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
