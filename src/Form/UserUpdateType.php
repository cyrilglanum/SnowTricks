<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, ['help' => "* Votre pseudo dans l'application",
                "attr" => [
                    'class' => 'col-12 mb-3',
                    'maxlength' => 8,
                    'minlength' => 2
                ],
                'trim' => true,
                'required' => 'required',
                'label' => "Identifiant",
                'empty_data' => true,
                'invalid_message' => 'L\'identifiant est requis.',
            ])
            ->add('image', FileType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3 mb-3'
                ],
                'mapped' => false,
                'required' => false,
                'label' => 'Image de profil',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
