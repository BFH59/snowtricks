<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',
                TextType::class,
                $this->getConfiguration('Prenom', 'Saisissez votre prenom'))
            ->add('lastName',
                TextType::class,
                $this->getConfiguration('Nom', 'Saisissez votre nom'))
            ->add('email',
                EmailType::class,
                $this->getConfiguration('Votre Email', 'Saisissez votre adresse email'))
            ->add('avatar',
                FileType::class,

                [
                    'label' => 'Avatar',
                    'attr' => [
                        'placeholder' => 'Uploadez un avatar'
                    ],
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Veuillez uploader une image jpeg ou png',
                        ])
                    ]
                ])
            ->add('hash',
                PasswordType::class,
                $this->getConfiguration('Mot de passe', 'saisissez votre mot de passe'))
            ->add('passwordConfirm',
                PasswordType::class,
                $this->getConfiguration('Mot de passe', 'confirmez votre mot de passe'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
