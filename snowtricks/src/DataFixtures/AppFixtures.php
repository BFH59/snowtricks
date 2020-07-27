<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Role;
use App\Entity\Trick;
use App\Entity\User;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        //creation du role membre
        $memberRole = new Role();
        $memberRole->setTitle('ROLE_MEMBER');
        $manager->persist($memberRole);

        $userRole = new Role();
        $userRole->setTitle('ROLE_USER');
        $manager->persist($userRole);


        //création des 3 catégories

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

        // creation fake user

        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 10; $i++) {


            $user = new User();
            $genre = $faker->randomElement($genres);

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname($genre))
                ->setLastName($faker->lastname)
                ->setEmail($faker->email)
                ->setHash($hash);
            $manager->persist($user);
            $users[] = $user;
        }

        $trick = new Trick();
        $trick->setTitle('figure mute')
              ->setContent("saisie de la carre frontside de la planche entre les deux pieds avec la main avant")
              ->setCoverImage("trick.jpeg")
              ->setCategory($grabsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                    ->setPath('uploads/image')
                  ->setCaption('image' .$j)
                  ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                    ->setContent($faker->sentence)
                    ->setCreatedAt(new \Datetime())
                    ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure style week')
            ->setContent("saisie de la carre backside de la planche, entre les deux pieds, avec la main avant")
            ->setCoverImage(mt_rand(1, 4).'.jpg')
            ->setCategory($grabsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }
        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }

        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure indy')
            ->setContent("saisie de la carre frontside de la planche, entre les deux pieds, avec la main arrière")
            ->setCoverImage("trick.jpeg")
            ->setCategory($grabsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure nose grab')
            ->setContent("saisie de la partie avant de la planche, avec la main avant")
            ->setCoverImage(mt_rand(1, 4).'.jpg')
            ->setCategory($grabsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure tail grab')
            ->setContent("saisie de la partie arrière de la planche, avec la main arrière")
            ->setCoverImage("trick.jpeg")
            ->setCategory($grabsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }

        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure truck driver')
            ->setContent("saisie du carre avant et carre arrière avec chaque main (comme tenir un volant de voiture)")
            ->setCoverImage(mt_rand(1, 4).'.jpg')
            ->setCategory($grabsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure rotation 180')
            ->setContent("un 180 désigne un demi-tour, soit 180 degrés d'angle")
            ->setCoverImage("trick.jpeg")
            ->setCategory($rotationCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure rotation 360')
            ->setContent("360, trois six pour un tour complet ")
            ->setCoverImage(mt_rand(1, 4).'.jpg')
            ->setCategory($rotationCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure rotation big foot')
            ->setContent("1080 ou big foot pour trois tours")
            ->setCoverImage(mt_rand(1, 4).'.jpg')
            ->setCategory($rotationCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }

        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure front flip')
            ->setContent("Un flip est une rotation verticale. On distingue les front flips, rotations en avant")
            ->setCoverImage("trick.jpeg")
            ->setCategory($flipsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $trick = new Trick();
        $trick->setTitle('figure back flip')
            ->setContent("Un flip est une rotation verticale. On distingue les back flips, rotations en arrière")
            ->setCoverImage(mt_rand(1, 4).'.jpg')
            ->setCategory($flipsCategory);

        for($j = 1; $j <= mt_rand(1, 4); $j++ ){

            $image = new Image();
            $image->setName($j.'.jpg')
                ->setPath('uploads/image')
                ->setCaption('image' .$j)
                ->setTrick($trick);

            $manager->persist($image);
        }

        for($v = 1; $v <= mt_rand(1,3); $v++){
            $video = new Video();
            $video->setUrl("https://www.youtube.com/watch?v=SQyTWk7OxSI")
                  ->setTrick($trick);

            $manager->persist($video);
        }

        for($c = 1; $c <= mt_rand(1,15); $c++){
            $user = $users[mt_rand(0, count($users) - 1)];
            $comment = new Comment();
            $comment->setAuthor($user)
                ->setContent($faker->sentence)
                ->setCreatedAt(new \Datetime())
                ->setTrick($trick);
            $manager->persist($comment);
        }
        $manager->persist($trick);

        $manager->flush();
    }
}
