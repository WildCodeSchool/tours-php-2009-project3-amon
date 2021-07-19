<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Image;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

class GalleryController extends AbstractController
{
    private $categories = [
        'outdoor layout' => 'Aménagement Extérieur',
        'privacy screen and sun visor' => 'Brise-vue et Pare-soleil',
        'decoration' => 'Décoration',
        'stairs' => 'Escaliers',
        'railing' => 'Garde-corps',
        'glass trap' => 'Trappes vitrées',
        'canopy' => 'Verrières',
        'mirror' => 'Miroirs',
        'library' => 'Bibliothèque',
        'other' => 'Autre',
        ];

    /**
     * @Route("/article/{image}", name="article_by_image_id")
     */
    public function showArticle(Image $image): Response
    {
        $article = $image->getArticle();
        return new JsonResponse(['data' => $article->getArray()]);
    }

    /**
     * @Route("/design-galerie/", name="design_gallery")
     */
    public function showImages(
        ImageRepository $imageRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $data = $imageRepository->findBy(
            ['category' => ['outdoor layout', 'privacy screen and sun visor', 'decoration', 'stairs',
            'railing', 'glass trap', 'canopy']],
            ['id' => 'DESC'],
        );
        $images = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );
        return $this->render(
            'gallery/design.html.twig',
            ['images' => $images, 'category' => 'all categories', ],
        );
    }


    /**
     * @Route("/design-galerie/{category}", name="design_gallery_category")
     */
    public function showImagesByCategory(
        Request $request,
        PaginatorInterface $paginator,
        string $category,
        ImageRepository $imageRepository
    ): Response {
        $images = [];
        $categories = [
            'outdoor layout',
            'privacy screen and sun visor',
            'decoration',
            'stairs',
            'railing',
            'glass trap',
            'canopy',
        ];
        if (!in_array($category, $categories)) {
            return $this->redirectToRoute('design_gallery');
        }
        $allImages = $imageRepository->findBy(
            ['category' => $category],
            ['id' => 'DESC']
        );
        foreach ($allImages as $image) {
            $article = $image->getArticle();
            if (!empty($article)) {
                $images[] = $image;
            }
        }
        $data = $images;
        $images = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );
        if (!empty($data)) {
            return $this->render('gallery/design.html.twig', ['images' => $images, 'category' => $category]);
        } else {
            $this->addFlash(
                'empty_alert',
                'Désolé aucune image n\'est actuellement disponible pour la catégorie ' . $this->categories[$category],
            );
            return $this->redirectToRoute('design_gallery');
        }
    }

    /**
     * @Route("/passage-galerie", name="passage_gallery")
     */
    public function passageImages(
        ImageRepository $imageRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $data = $imageRepository->findBy(
            ['category' => ['mirror', 'library']]
        );

        $images = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );
        return $this->render(
            'gallery/passage.html.twig',
            ['images' => $images, 'category' => 'all categories']
        );
    }

    /**
     * @Route("/passage-galerie/{category}", name="passage_gallery_category")
     */
    public function passageImagesByCategory(
        string $category,
        ImageRepository $imageRepository,
        Request $request,
        PaginatorInterface $paginator
    ): Response {
        $images = [];
        $categories = [
            'mirror',
            'library',
        ];
        if (!in_array($category, $categories)) {
            return $this->redirectToRoute('passage_gallery');
        }
        $allImages = $imageRepository->findBy(
            ['category' => $category],
            ['id' => 'DESC']
        );
        foreach ($allImages as $image) {
            $article = $image->getArticle();
            if (!empty($article)) {
                if ($image->getCategory() !== 'other') {
                    $images[] = $image;
                };
            }
        }
        $data = $images;
        $images = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            15
        );
        if (!empty($data)) {
            return $this->render('gallery/passage.html.twig', ['images' => $images, 'category' => $category]);
        } else {
            $this->addFlash(
                'empty_alert',
                'Désolé aucune image n\'est actuellement disponible pour la catégorie ' . $this->categories[$category],
            );
            return $this->redirectToRoute('passage_gallery');
        }
    }

    /**
     * @Route("/actualites/{id}/delete", name="actuality_delete", methods="DELETE")
     */
    public function deleteArticle(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }
        return $this->redirectToRoute('actuality');
    }
}
