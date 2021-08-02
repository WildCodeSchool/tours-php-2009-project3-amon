<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;

class DesignController extends AbstractController
{
    /**
     * Returning "Design métallique" page with up to the 3 last articles posted for the carousel
     * @Route("/design", name="design_index")
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        $news = $articleRepository->findby(
            ['isNews' => true],
            ['date' => 'DESC'],
            3,
            0,
        );
        return $this->render('design/index.html.twig', ['news' => $news]);
    }
}
