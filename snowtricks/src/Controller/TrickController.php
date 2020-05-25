<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\Trick;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{

    /**
     * function that allow trick creation
     * @Route("trick/new", name="trick_create")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverImage = $form['coverImage']->getData();
            if ($coverImage) {
                $coverImageFileName = $fileUploader->upload($coverImage);
                $trick->setCoverImage($coverImageFileName);
            }

            $manager->persist($trick);
            $manager->flush();
        }
        return $this->render('trick/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * function to display single trick
     * @Route("trick/{slug}", name="trick_show")
     * @param Trick $trick
     * @return Response
     */
    public function show(Trick $trick)
    {
        return $this->render('trick/show.html.twig', [
            'trick' => $trick
        ]);
    }
}
