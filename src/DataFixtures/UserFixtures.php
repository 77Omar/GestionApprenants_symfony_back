<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Entity\Cm;
use App\Entity\Formateur;
use App\Entity\User;
use App\Entity\Admin;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $encoder;
    public function  __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder=$encoder;
    }
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');
        $Tab=[User::class, Apprenant::class, Formateur::class, Cm::class];

        for($p=0; $p<count($Tab); $p++ ){
            $user = new $Tab[$p];
            $hash = $this->encoder->encodePassword($user, 'password');
            $user->setFirstName($faker->firstName())
                 ->setLastName($faker->lastName)
                 ->setEmail($faker->email)
                 ->setPassword($hash)
                 ->setAvatar($faker->imageUrl(250,250));
            $user->setProfil($this->getReference(ProfilFixtures:: Profil_User.$p));
            $manager->persist($user);
        }

        $manager->flush();
    }
}
