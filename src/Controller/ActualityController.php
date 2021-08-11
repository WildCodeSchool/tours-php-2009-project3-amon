<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;

class ActualityController extends AbstractController
{
    /**
     * @Route("/actualites", name="actuality")
     */
    public function showArticles(ArticleRepository $articleRepository): Response
    {
        $news = $articleRepository->findBy(
            ['isNews' => true],
            ['date' => 'DESC'],
        );
        return $this->render('actuality/index.html.twig', ['news' => $news]);
    }
}
