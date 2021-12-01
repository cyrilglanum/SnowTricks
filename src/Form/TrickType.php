<?php


namespace App\Form;


namespace App\Form;

use App\Entity\Tricks;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
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
                    'class' => 'col-12 mb-3'
                ],
            ])
            ->add('img_background', FileType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'mapped' => false,
                'required' => false,

            ])
            ->add('description', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
            ])
            ->add('groupe', TextType::class, [
                "attr" => [
                    'class' => 'col-12'
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tricks::class,
        ]);
    }
}