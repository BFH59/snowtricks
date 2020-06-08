<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * Allow new category creation
     * @Route("/category/new", name="create_category")
     * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $manager)
    {
        $category = new Category();
        $categories = $categoryRepository->findAll();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($category);
            $manager->flush();

            $this->addFlash(
                'success',
                'La catégorie a bien été enregistrée');
            return $this->redirectToRoute('trick_create');
        }


        return $this->render('category/new.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
}
