<?php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImageType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('url',
                FileType::class,

                    [
                        'label' => "Images",
                        'attr' => [
                            'placeholder' => 'Uploadez une image'
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
            ->add('caption', TextType::class, [
                'attr' => [
                    'placeholder' => "titre de l'image"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
        ]);
    }
}
