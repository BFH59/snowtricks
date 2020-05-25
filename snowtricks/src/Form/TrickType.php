<?php

namespace App\Form;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class TrickType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
            $builder
                ->add(
                    'title',
                    TextType::class,
                    $this->getConfiguration('Titre', 'Titre de la figure'))
                ->add(
                    'slug',
                    TextType::class,
                    $this->getConfiguration('Chaine URL', 'Adresse Web(automatique)',[
                        'required' => false
                    ]))
                ->add('coverImage',
                    FileType::class,
                    $this->getConfiguration("Image principale","Uploadez l'image principale",
                        [
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
                        ]))
                ->add(
                    'content',
                    TextType::class,
                    $this->getConfiguration('Contenu', 'Tapez le contenu de la figure'))
                ->add(
                    'category',
                    EntityType::class,
                    $this->getConfiguration('Catégorie', 'Choisissez la catégorie de la figure',[
                        "class" => 'App\Entity\Category'
                    ])
                )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
