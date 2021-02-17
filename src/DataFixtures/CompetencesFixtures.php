<?php

namespace App\DataFixtures;
use App\Entity\Competences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompetencesFixtures extends Fixture
{
   // public const competence = 'Competences';

    public function load(ObjectManager $manager)
    {

        $manager->flush();

    }

}
