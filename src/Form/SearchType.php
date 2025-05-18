<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as SearchFieldType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire de recherche pour les livres
 * Permet de filtrer par titre, auteur et genre
 */
class SearchType extends AbstractType
{
    /**
     * Construction du formulaire avec ses champs
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ de recherche par titre
            ->add('titre', SearchFieldType::class, [
                'label' => 'Titre',
                'required' => false, // Champ facultatif
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Rechercher par titre...'
                ]
            ])
            // Liste déroulante des auteurs
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class, // Entité utilisée
                'choice_label' => 'nom', // Champ à afficher
                'label' => 'Auteur',
                'required' => false, // Champ facultatif
                'placeholder' => 'Tous les auteurs',
                'attr' => ['class' => 'form-select']
            ])
            // Liste déroulante des genres
            ->add('genre', EntityType::class, [
                'class' => Genre::class, // Entité utilisée
                'choice_label' => 'nom', // Champ à afficher
                'label' => 'Genre',
                'required' => false, // Champ facultatif
                'placeholder' => 'Tous les genres',
                'attr' => ['class' => 'form-select']
            ])
            // Bouton de soumission
            ->add('rechercher', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary mt-3'],
                'label' => 'Rechercher'
            ])
        ;
    }

    /**
     * Configuration des options du formulaire
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'method' => 'GET', 
            'csrf_protection' => false,
            'allow_extra_fields' => true,
        ]);
    }
} 