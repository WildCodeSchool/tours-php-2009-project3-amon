<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Image::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name', 'Nom'),
            ChoiceField::new('category', 'Catégorie')
                ->setChoices([
                    'Aménagement Extérieur' => 'outdoor layout',
                    'Brise-vue et Pare-soleil' => 'privacy screen and sun visor',
                    'Décoration' => 'decoration',
                    'Escaliers' => 'stairs',
                    'Garde-corps' => 'railing',
                    'Trappes vitrées' => 'glass trap',
                    'Verrières' => 'canopy',
                    'Miroirs' => 'mirror',
                    'Bibliothèque' => 'library',
                    'Autre' => 'other',
                ])
                ->allowMultipleChoices(false)
                ->renderExpanded(true),
            TextField::new('alternativeText', 'Texte alternatif')
                ->setSortable(false),
            ImageField::new('url', 'Image')
                ->onlyOnIndex()
                ->setBasePath($this->getParameter('upload_directory'))
                ->setSortable(false),
            Field::new('urlFile', 'Image')
                ->onlyOnForms()
                ->setFormType(VichImageType::class)
                ->setFormTypeOption('allow_delete', false),
            AssociationField::new('article'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        $customDelete = Action::new('CustomDelete', 'Supprimer')
            ->setIcon('fa fa-trash')
            ->addCssClass('text-danger modal-trigger')
            ->linkToCrudAction('customDelete');
        $actions
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->add(Crud::PAGE_INDEX, $customDelete)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setIcon('fa fa-pen');
            })
            ->setPermission($customDelete, 'ROLE_ADMIN');
        return $actions
            ->reorder(CRUD::PAGE_INDEX, [Action::EDIT]);
    }

    /**
     * @param Assets $assets
     * @return Assets
     */
    public function configureAssets(Assets $assets): Assets
    {
        $modal =
            '<div id="modalContainer" style="display: none">
                <div id="modal">
                    Êtes-vous sûr de vouloir supprimer cette image ? Attention cette action est irréversible.
                    <div id="choices">
                        <button name="confirmAction" type="button" class="choiceBtn" id="confirmAction">
                            <a id="confirmActionLink" href="#" data-action-name="CustomDelete">Confirmer</a>
                        </button>
                        <button name="cancelAction" type="button" class="choiceBtn" id="cancelAction">
                            Annuler
                        </button>
                    </div>
                </div>
            </div>';
        return $assets
            ->addWebpackEncoreEntry('confirmationModal')
            ->addHtmlContentToBody($modal);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setEntityLabelInSingular('Image')
            ->setEntityLabelInPlural('Images');
    }

    /**
     * @param AdminContext $adminContext
     * @param EntityManager $entityManager
     * @return RedirectResponse
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function customDelete(AdminContext $adminContext, EntityManagerInterface $entityManager): RedirectResponse
    {
        $id = $adminContext->getRequest()->query->get('entityId');
        $image = $this->getDoctrine()->getRepository(Image::class)->find($id);
        if ($image && in_array('ROLE_ADMIN', $adminContext->getUser()->getRoles())) {
            if ($image->getArticle()->getImages()->count() > 1) {
                $entityManager->remove($image);
                $entityManager->flush();
                $this->addFlash(
                    'success',
                    '"' . $image->getName() . '"'  . ' a été supprimée.'
                );
            } else {
                $this->addFlash(
                    'warning',
                    '"' . $image->getName() . '"'  . ' est la dernière image de votre article.'
                );
                $routeBuilder = $this->get(AdminUrlGenerator::class);
                return $this->redirect(
                    $routeBuilder
                        ->unsetAll()
                        ->setController(ArticleCrudController::class)
                        ->setAction(Action::EDIT)
                        ->set('menuIndex', '1')
                        ->setEntityId($image->getArticle()->getId())
                        ->generateUrl()
                );
            }
        }
        $routeBuilder = $this->get(AdminUrlGenerator::class);
        return $this->redirect(
            $routeBuilder
                ->unsetAll()
                ->setController(ImageCrudController::class)
                ->set('menuIndex', '2')
                ->generateUrl()
        );
    }
}
