<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Contrôleur gérant la page d'accueil de la bibliothèque
 */
class HomeController extends AbstractController
{
    /**
     * Affiche la page d'accueil avec la recherche et les livres populaires
     */
    #[Route('/', name: 'app_home')]
    public function index(Request $request, LivreRepository $livreRepository): Response
    {
        // Création du formulaire de recherche
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        
        // Initialisation du tableau des livres
        $livres = [];
        
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
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
        }
        
        // Récupération des livres populaires (ici, les 6 plus récents)
        $livresPopulaires = $livreRepository->findBy([], ['id' => 'DESC'], 6);
        
        // Rendu de la vue avec les données
        return $this->render('home/index.html.twig', [
            'search_form' => $form->createView(),
            'livres' => $livres,
            'livres_populaires' => $livresPopulaires,
            'recherche_effectuee' => $form->isSubmitted() && $form->isValid(),
        ]);
    }
} 