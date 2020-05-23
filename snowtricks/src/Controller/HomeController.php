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
        $tricks = $repo->findAll();

        return $this->render(
            'home.html.twig',
            [
                'tricks' => $tricks,
            ]
        );
    }
}