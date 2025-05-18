<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }

    public function save(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Livre $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Recherche des livres par critères
     * 
     * @param string|null $titre Titre du livre
     * @param int|null $auteurId ID de l'auteur
     * @param int|null $genreId ID du genre
     * @return Livre[] Liste des livres correspondant aux critères
     */
    public function findBySearchCriteria(?string $titre = null, ?int $auteurId = null, ?int $genreId = null): array
    {
        // Création du QueryBuilder
        $queryBuilder = $this->createQueryBuilder('l')
            ->orderBy('l.titre', 'ASC');
        
        // Ajout de la condition sur le titre
        if ($titre) {
            $queryBuilder->andWhere('l.titre LIKE :titre')
                ->setParameter('titre', '%' . $titre . '%');
        }
        
        // Ajout de la condition sur l'auteur
        if ($auteurId) {
            $queryBuilder->andWhere('l.auteur = :auteurId')
                ->setParameter('auteurId', $auteurId);
        }
        
        // Ajout de la condition sur le genre
        if ($genreId) {
            $queryBuilder->andWhere('l.genre = :genreId')
                ->setParameter('genreId', $genreId);
        }
        
        // Exécution de la requête et récupération des résultats
        return $queryBuilder->getQuery()->getResult();
    }
} 