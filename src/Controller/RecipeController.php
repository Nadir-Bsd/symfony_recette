<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

/**
 * Controller gérant les opérations CRUD pour l'entité Recipe
 */
#[Route('/recipe')]
final class RecipeController extends AbstractController
{
    /**
     * Affiche la liste de toutes les recettes
     * 
     * @param RecipeRepository $recipeRepository Service d'accès aux recettes
     * @return Response Vue avec la liste des recettes
     */
    #[Route(name: 'app_recipe', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    /**
     * Crée une nouvelle recette
     * 
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités Doctrine
     * @return Response Redirection vers la liste ou formulaire avec erreurs
     */
    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de Recipe
        $recipe = new Recipe();
        
        // Création et gestion du formulaire
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        // Validation et persistance des données
        if ($form->isSubmitted() && $form->isValid()) {

            $recipe->setUser($this->getUser());

            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * Affiche les détails d'une recette spécifique
     * 
     * @param Recipe $recipe Recette à afficher (injection automatique par Symfony)
     * @return Response Vue détaillée de la recette
     */
    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    /**
     * Modifie une recette existante
     * 
     * @param Request $request Requête HTTP
     * @param Recipe $recipe Recette à modifier
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response Redirection ou formulaire avec erreurs
     */
    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        // Création du formulaire pré-rempli avec les données existantes
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        // Validation et mise à jour des données
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    /**
     * Supprime une recette
     * 
     * @param Request $request Requête HTTP
     * @param Recipe $recipe Recette à supprimer
     * @param EntityManagerInterface $entityManager Gestionnaire d'entités
     * @return Response Redirection vers la liste des recettes
     */
    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{slugCategorie}/recettes', name: 'app_recipe_category', methods: ['GET'], requirements: ['slugCategorie' => '[a-z0-9-]+'])]
    public function recipeByCategory(string $slugCategorie, RecipeRepository $recipeRepository): Response
    {
        $recipes = $recipeRepository->findAllByCategorySlug($slugCategorie);

        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipes,
            'categoryName' => $recipes[0]->getCategory()->getName(),
        ]);
    }
}
