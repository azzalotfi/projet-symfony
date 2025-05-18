<?php

namespace App\Repository;

use App\Entity\Emprunt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Emprunt>
 */
class EmpruntRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Emprunt::class);
    }

    public function save(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Emprunt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Find all emprunts that are due soon (within the next 3 days)
     */
    public function findSoonDue(\DateTimeInterface $today): array
    {
        $threeDaysLater = (new \DateTime($today->format('Y-m-d')))->modify('+3 days');

        
        return $this->createQueryBuilder('e')
            ->andWhere('e.date_retour >= :today')
            ->andWhere('e.date_retour <= :threeDaysLater')
            ->setParameter('today', $today)
            ->setParameter('threeDaysLater', $threeDaysLater)
            ->orderBy('e.date_retour', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
} 