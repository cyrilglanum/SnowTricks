<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'label' => 'Email',
                'mapped' => false,
                'disabled' => true,
                'data' => $options['data']->request->get('userEmail'),
            ])
            ->add('newPassword', PasswordType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'label' => 'Nouveau mot de passe',
                'data_class' => null,
                'mapped' => false,
            ])
            ->add('token', TextType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3'
                ],
                'data' => $options['data']->attributes->get('token'),
                'data_class' => null,
                'mapped' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
