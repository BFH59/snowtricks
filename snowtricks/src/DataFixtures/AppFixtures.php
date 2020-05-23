<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        //création des 3 catégories
        /**
        $trickCats = ["GRABS", "ROTATION", "FLIPS"];
        foreach ($trickCats as $singleCat) {
            $category = new Category();
            $category->setTitle($singleCat);
            $this->addReference($singleCat, $category);
            $manager->persist($category);
        }*/

        $grabs = new Category();
        $grabs->setTitle('GRABS');
        $manager->persist($grabs);

        $rotation = new Category();
        $rotation->setTitle('ROTATION');
        $manager->persist($rotation);

        $flips = new Category();
        $flips->setTitle('FLIPS');
        $manager->persist($flips);

        $this->addReference('category-grabs', $grabs);
        $this->addReference('category-rotation', $rotation);
        $this->addReference('category-flips', $flips);


        $grabsCategory = $this->getReference('category-grabs');
        $rotationCategory = $this->getReference('category-rotation');
        $flipsCategory = $this->getReference('category-flips');

        $trick = new Trick();
        $trick->setTitle('figure mute')
              ->setContent("saisie de la carre frontside de la planche entre les deux pieds avec la main avant")
              ->setCoverImage("http://placehold.it/200x200")
              ->setCategory($grabsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure style week')
            ->setContent("saisie de la carre backside de la planche, entre les deux pieds, avec la main avant")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($grabsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure indy')
            ->setContent("saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($grabsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure nose grab')
            ->setContent("saisie de la partie avant de la planche, avec la main avant")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($grabsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure tail grab')
            ->setContent("saisie de la partie arrière de la planche, avec la main arrière")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($grabsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure truck driver')
            ->setContent("saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($grabsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure rotation 180')
            ->setContent("un 180 désigne un demi-tour, soit 180 degrés d'angle")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($rotationCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure rotation 360')
            ->setContent("360, trois six pour un tour complet ")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($rotationCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure rotation big foot')
            ->setContent("1080 ou big foot pour trois tours")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($rotationCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure front flip')
            ->setContent("Un flip est une rotation verticale. On distingue les front flips, rotations en avant")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($flipsCategory);
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure back flip')
            ->setContent("Un flip est une rotation verticale. On distingue les back flips, rotations en arrière")
            ->setCoverImage("http://placehold.it/200x200")
            ->setCategory($flipsCategory);
        $manager->persist($trick);

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
