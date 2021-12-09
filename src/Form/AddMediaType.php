<?php


namespace App\Form;


namespace App\Form;

use App\Entity\Media;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddMediaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url_img', FileType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'mapped' => false,
                'label' => 'Image',
                'required' => false,
            ])
            ->add(
                'type',
                ChoiceType::class,
                [
                    'choices' => [
                        '# Image ~' => 'IMG',
                        '# Video ' => 'VID',
                    ],
                    "attr" => [
                        'class' => 'col-12 mb-3'
                    ],
                    'expanded' => true,
                    'required' => true
                ]
            )
            ->add('url_video', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'label' => 'Url du média (si vidéo)',
                'mapped' => false,
                'required' => false,

            ])
            ->add('description', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'required' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
        ]);
    }
}