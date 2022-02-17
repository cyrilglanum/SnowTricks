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
                    'maxlength' => 8
                ],
                'label' => "Identifiant",
                'invalid_message' => 'L\'identifiant est requis.',
            ])
//            ->add('email', EmailType::class, [
//                'invalid_message' => 'L\'email est obligatoire',
//                "attr" => [
//                    'class' => 'col-12 mb-3'
//                ],
//            ])
            ->add('image', FileType::class, [
                "attr" => [
                    'class' => 'col-12 mb-3 mb-3'
                ],
                'mapped' => false,
                'required' => false,
                'label' => 'Image de profil',
            ]);
//            ->add('password', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'Les mots de passe doivent correspondre.',
//                'options' => ['attr' => ['class' => 'password-field']],
//                'required' => true,
//                'first_options'  => ['label' => 'Password'],
//                'second_options' => ['label' => 'Repeat Password'],
//                ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}