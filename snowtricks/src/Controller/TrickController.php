<?php

namespace App\Controller;

use App\Entity\Image;
use App\Service\FileUploader;
use App\Entity\Trick;
use App\Form\TrickType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
            //manage cover image
            if ($coverImage) {
                $coverImageFileName = $fileUploader->upload($coverImage);
                $trick->setCoverImage($coverImageFileName);
            }

            //manage others images uploaded by user
            $pos = 0;
            if(isset($form['images'][0]['url'])) {
                if ($form['images'][$pos]['url']->getData()) {
                    foreach ($trick->getImages() as $images) {
                        $images->setUrl($fileUploader->upload($form['images'][$pos]['url']->getData()));
                        $images->setTrick($trick);
                        $manager->persist($images);
                        $pos++;
                    }
                }
            }
            foreach ($trick->getVideos() as $videos){
                $videos->setTrick($trick);
                $manager->persist($videos);
            }

            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre figure <strong> {$trick->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }
        return $this->render('trick/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/trick/{slug}/edit", name="trick_edit")
     * @Security("is_granted('ROLE_USER')", message="Cette annonce ne vous appartient pas. Vous ne pouvez pas la modifier")
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $manager
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function edit(Request $request, Trick $trick, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $coverImage = $form['coverImage']->getData();
            //manage cover image
            if ($coverImage) {
                $coverImageFileName = $fileUploader->upload($coverImage);
                $trick->setCoverImage($coverImageFileName);
            }

            //manage others images uploaded by user
            $pos = 0;
            if ($form['images'][$pos]['url']->getData()) {
                foreach ($trick->getImages() as $images) {
                    $images->setUrl($fileUploader->upload($form['images'][$pos]['url']->getData()));
                    $images->setTrick($trick);
                    $manager->persist($images);
                    $pos++;
                }
            }
            foreach ($trick->getVideos() as $videos){
                $videos->setTrick($trick);
                $manager->persist($videos);
            }
            $manager->persist($trick);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de la figure : <strong> {$trick->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('trick_show',[
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' =>$trick
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
