<?php

namespace App\Controller;


use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * display homepage with full list of tricks
     * @Route("/", name="homepage")
     * @param TrickRepository $repo
     * @return Response
     */
    public function home(TrickRepository $repo)
    {
        $tricks = $repo->findBy([], ['createdAt' => 'ASC'], 10, 0);

        return $this->render(
            'home.html.twig',
            [
                'tricks' => $tricks,
            ]
        );
    }

    /**
     * @Route("/{start}", name="moreTricks", requirements={"start": "\d+"})
     * @param TrickRepository $repo
     * @param int $start
     * @return Response
     */
    public function moreTricks(TrickRepository $repo, $start = 10){
        $tricks = $repo->findBy([], ['createdAt' => 'ASC'], 5, $start);

        return $this->render('home/moreTricks.html.twig', [
            'tricks' => $tricks
        ]);
    }
}