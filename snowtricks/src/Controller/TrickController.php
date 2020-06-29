<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Form\CommentType;
use App\Service\FileUploader;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_MEMBER", message="Votre compte doit etre validé pour créer une figure")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param ImageUploader $imageUploader
     * @param FileUploader $fileUploader
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager, ImageUploader $imageUploader, FileUploader $fileUploader)
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
            foreach($trick->getImages() as $image)
            {
                $image->setTrick($trick);
                $image = $imageUploader->uploadImage($image);
                $manager->persist($image);
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
     * @IsGranted("ROLE_MEMBER", message="Votre compte doit etre validé pouvoir éditer cette figure")
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $manager
     * @param ImageUploader $imageUploader
     * @param FileUploader $fileUploader
     * @return Response
     * @throws \Exception
     */
    public function edit(Request $request, Trick $trick, EntityManagerInterface $manager, ImageUploader $imageUploader, FileUploader $fileUploader)
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
            foreach($trick->getImages() as $image)
            {

                $image->setTrick($trick);
                $image = $imageUploader->uploadImage($image);
                $manager->persist($image);
            }
            foreach ($trick->getVideos() as $videos){
                $videos->setTrick($trick);
                $manager->persist($videos);
            }

            $trick->setUpdatedAt(new \DateTime());
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
            'trick' => $trick
        ]);
    }

    /**
     * function to display single trick
     * @Route("trick/{slug}", name="trick_show")
     * @param Trick $trick
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function show(Trick $trick, Request $request, EntityManagerInterface $manager)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $author = $this->getUser();
            $comment->setAuthor($author)
                    ->setTrick($trick);
            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre commentaire a bien été enregistré !"
            );

            return $this->redirectToRoute('trick_show',[
                'slug' => $trick->getSlug()
            ]);

        }
        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView()
        ]);
    }
}
