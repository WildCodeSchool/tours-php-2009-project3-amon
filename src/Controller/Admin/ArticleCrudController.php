<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('title', 'Titre')
                ->setRequired(true),
            DateField::new('date', 'Le')
                ->setFormat('dd / MM / yyyy')
                ->setRequired(true),
            TextEditorField::new('content', 'Contenu')
                ->setRequired(true)
                ->setSortable(false),
            BooleanField::new('isNews', 'Actualité'),
            AssociationField::new('images', 'Image(s)')
                ->onlyOnIndex()
                ->setTextAlign('center')
                ->setSortable(false)
                ->formatValue(function ($value, $entity) {
                    $images = '<div style="display: flex; flex-flow: row wrap; align-content: center; justify-content: space-evenly;">';
                    foreach ($entity->getImages() as $image) {
                        $images = sprintf(
                            "%s<img src=\"%s/%s\" alt=\"%s\" style=\"margin-bottom: 5px; max-width: 100%%;\" >",
                            $images,
                            $this->getParameter('upload_directory'),
                            $image->getUrl(),
                            $image->getAlternativeText()
                        );
                    }
                    return $images . '</div>';
                }),
            AssociationField::new('images', 'Image(s)')
                ->onlyOnDetail()
                ->setSortable(false)
                ->formatValue(function ($value, $entity) {
                    $images = '<div style="display: flex; flex-flow: row wrap; align-content: center; justify-content: space-evenly;">';
                    foreach ($entity->getImages() as $image) {
                        $images = sprintf(
                            "%s<img src=\"%s/%s\" alt=\"%s\" style=\"margin-bottom: 5px; max-width: 100%%\" >",
                            $images,
                            $this->getParameter('upload_directory'),
                            $image->getUrl(),
                            $image->getAlternativeText()
                        );
                    }
                    return $images . '</div>';
                }),
            CollectionField::new('images', 'Image(s)')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(ImageType::class)
                ->setRequired(true)
                ->setSortable(false)
                ->hideOnDetail()
                ->hideOnIndex(),
            ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles');
    }
}
