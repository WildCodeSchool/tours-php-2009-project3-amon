<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Image;
use App\Form\ImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
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
                ->setRequired(true),
            BooleanField::new('isNews', 'Actualité'),
            CollectionField::new('images', 'Image(s)')
                ->allowAdd()
                ->allowDelete()
                ->setEntryType(ImageType::class)
                ->setRequired(true),
            ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Article')
            ->setEntityLabelInPlural('Articles');
    }
}
