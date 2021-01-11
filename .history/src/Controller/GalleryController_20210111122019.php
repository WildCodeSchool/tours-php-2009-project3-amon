<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Image;
use App\Repository\ImageRepository;
use App\Form\EditFormType;

class GalleryController extends AbstractController
{
    /**
     * @Route("/gallerie-design-metallique/", name="gallery_metallicDesign")
     */
    public function showImages(ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findAll();
        return $this->render('gallery/metallicDesign.html.twig', ['images' => $images]);
    }

    /**
     * @Route("/gallerie-design-metallique/{categorie}", name="gallery_metallicDesign_category")
     */
    public function showImagesByCategorie(string $categorie, ImageRepository $imageRepository): Response
    {
        $images = $imageRepository->findBy([
            'categorie' => $categorie
        ]);
        return $this->render('gallery/metallicDesign.html.twig', ['images' => $images]);
    }

    /**
     * @Route("/gallerie-design-metallique/{categorie}/{id}",
     *          name="gallery_metallicDesign_category_showImage", methods={"GET"})
     */
    public function showImage(Image $image, string $categorie): Response
    {
        return $this->render('gallery/showImg.html.twig', [
            'categorie' => $categorie,
            'image' => $image,
        ]);
    }

    /**
     * @Route("/gallerie-design-metallique/{categorie}/{id}/edit",
     *      name="gallery_metallicDesign_category_showImage_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditFormType::class, $image);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('gallery_metallicDesign');
        }

        return $this->render('form/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/gallerie-design-metallique/{categorie}/{id}/delete",
     *          name="gallery_metallicDesign_category_showImg_delete", methods="DELETE")
     */
    public function delete(Request $request, Image $image, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('gallery_metallicDesign');
    }


    /**
     * @Route("/gallerie-passages-secrets", name="gallerie-passages-secrets")
     */
    public function secretPassagesGallery(): Response
    {
        return $this->render('gallery/secret_passages.html.twig');
    }
}
