<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur pour la recherche avancée des livres
 * Toutes les routes commencent par /search
 */
#[Route('/search')]
class SearchController extends AbstractController
{
    /**
     * Page de recherche avancée avec affichage des résultats
     */
    #[Route('', name: 'app_search')]
    public function index(Request $request, LivreRepository $livreRepository): Response
    {
        // Création du formulaire de recherche
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        
        // Valeurs par défaut
        $livres = [];
        $recherche_effectuee = false;
        
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            $recherche_effectuee = true;
            
            // Récupération des données du formulaire
            $data = $form->getData();
            
            // Récupération des critères de recherche
            $titre = $data['titre'] ?? null;
            $auteur = $data['auteur'] ?? null;
            $genre = $data['genre'] ?? null;
            
            // Recherche des livres correspondant aux critères
            $livres = $livreRepository->findBySearchCriteria(
                $titre,
                $auteur ? $auteur->getId() : null,
                $genre ? $genre->getId() : null
            );
        } else {
            // Par défaut, afficher tous les livres
            $livres = $livreRepository->findAll();
        }
        
        // Rendu de la page de recherche avec les résultats
        return $this->render('search/index.html.twig', [
            'search_form' => $form->createView(),
            'livres' => $livres,
            'recherche_effectuee' => $recherche_effectuee,
        ]);
    }
} 