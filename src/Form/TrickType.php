<?php

namespace App\Form;

use App\Entity\Tricks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrickType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3',
                    'required' => true
                ],
                'label' => 'Nom de la figure *',

            ])
            ->add('img_background', FileType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'mapped' => false,
                'required' => false,
                'label' => 'Image de présentation *',
            ])
//            ->add('images', FileType::class, [
//                "attr" => [
//                    'class' => 'col-12 mb-3'
//                ],
//                'mapped' => false,
//                'required' => false,
//                'multiple' => true,
//            ])
//            ->add('medias', FileType::class, array('required' => false))
            ->add('medias', CollectionType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'entry_type' => MediaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'mapped' => false,

            ])
            ->add('videos', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'mapped' => false,
                'required' => false,
                'label' => 'Videos intégrées',
                'help' => 'Url partage youtube séparées par une ","',
            ])
            ->add('description', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3',
                    'required' => true
                ],
                'label' => 'Description *',
            ])
            ->add('groupe', TextType::class, [
                "attr" => [
                    'class' => 'col-12',
                    'required' => true
                ],
                'label' => 'Groupe *',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}
