<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AccountType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName',
                TextType::class,
                $this->getConfiguration('Prenom', 'Modifiez votre prenom'))
            ->add('lastName',
                TextType::class,
                $this->getConfiguration('Nom', 'Modifiez votre nom'))
            ->add('email',
                EmailType::class,
                $this->getConfiguration('Votre Email', 'Modifiez votre adresse email'))
            ->add('avatar',
                FileType::class,

                [
                    'label' => 'Avatar',
                    'attr' => [
                        'placeholder' => 'Changer mon avatar'
                    ],
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Modifiez votre avatar',
                        ])
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
