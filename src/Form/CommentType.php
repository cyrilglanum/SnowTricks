<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'label' => 'Votre commentaire',
            ])
            ->add('trick_id', HiddenType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'data' => $options['data']['trick']->getId()

            ])
            ->add('user_id', HiddenType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'data' => $options['data']['user_id']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
