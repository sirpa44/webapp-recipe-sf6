<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredients', name: 'app_ingredients', methods: 'GET')]
    public function index(IngredientRepository $ingredientRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $ingredientRepository->findAll(),
            $request->query->getInt('page', 1),
            10,
        );

        return $this->render('pages/ingredient/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    #[Route('/ingredient/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'your new ingredient is added successfully');
            $ingredient = $form->getData();
            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('app_ingredients');
        }

        return $this->renderForm('pages/ingredient/form.html.twig', [
            'form' => $form,
            'title' => "Create new ingredient"
        ]);
    }

    #[Route('/ingredient/{ingredient}/update', name: 'app_ingredient_update', methods: ['GET', 'POST'])]
    public function update( Ingredient $ingredient, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->addFlash('success', 'your new ingredient is updated successfully');
            $ingredient = $form->getData();
            $em->persist($ingredient);
            $em->flush();

            return $this->redirectToRoute('app_ingredients');
        }

        return $this->renderForm('pages/ingredient/form.html.twig', [
            'form' => $form,
            'title' => "Update ingredient",
        ]);
    }
}
