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
use App\Form\ArticleFormType;

class ActualityController extends AbstractController
{
    /**
     * @Route("/actualites", name="actuality")
     */
    public function showArticles(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findBy(
            ["isNews" => true],
            ['date' => 'DESC'],
        );
        return $this->render('actuality/index.html.twig', ['articles' => $articles]);
    }

    /**
     * Returning the 3 last articles posted, for the carousel in "Design métallique"
     * @return object[]
     */
    public function showCarouselArticles(ArticleRepository $articleRepository): array
    {
        return $articleRepository->findby(
            ['isNews' => true],
            ['date' => 'DESC'],
            3,
            0,
        );
    }

    /**
     * Editing an article, but can not delete image for now, or it goes out of the db too.
     * @Route("/actualites/{id}/edit", name="actuality_edit")
     */
    public function editArticles(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('actuality');
        }

        return $this->render('actuality/edit.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }
}
