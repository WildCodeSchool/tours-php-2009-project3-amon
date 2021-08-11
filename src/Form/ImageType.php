<?php

namespace App\Form;

use App\Entity\Image;
use http\Client\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image as ImageConstraint;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
            ])
            ->add('urlFile', VichImageType::class, [
                'label' => 'Image',
                'constraints' => [
                    new ImageConstraint([
                        'mimeTypes' => [
                            'image/*',
                        ],
                    ]),
                ],
                'required' => !($_REQUEST['crudAction'] === 'edit'),
                'allow_delete' => false,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
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
                ],
                'label' => 'Catégorie',
                'required' => true,
            ])
            ->add('alternativeText', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
