<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ImageFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('urlFile', VichFileType::class, [
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/gif',
                            'image/png',
                        ]
                    ]),
                ],
                'allow_delete' => true,
                'download_uri' => true,
                'label' => 'Selectionner une photo'])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Amenagement extérieur' => 'Amenagement extérieur',
                    'Brise-vue et pare-soleil' => 'Brise-vue et pare-soleil',
                    'Décoration' => 'Décoration',
                    'Escaliers' => 'Escaliers',
                    'Garde-corps' => 'Garde-corps',
                    'Trappes vitrées' => 'Trappes vitrées',
                    'Verrière' => 'Verrières',
                    'Bibliothèques' => 'Bibliothèques',
                    'Miroirs' => 'Miroirs'
                ]
            ])
            ->add('texte_alternatif', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('otherImages', HiddenType::class, [
                'mapped' => false,
                'label' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
