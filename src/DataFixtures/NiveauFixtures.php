<?php

namespace App\DataFixtures;
use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        //}
        $manager->flush();

    }
}
