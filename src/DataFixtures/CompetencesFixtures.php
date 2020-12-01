<?php

namespace App\DataFixtures;
use App\Entity\Competences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompetencesFixtures extends Fixture
{
    public const competence = 'Competences';

    public function load(ObjectManager $manager)
    {
        $tabDev=['javascript','mysql', 'php'];

        for ($c=0; $c<count($tabDev); $c++){
            $comp= new Competences();
            $comp->setLibelle($tabDev[$c]);
            $this->addReference(self::competence.$c ,$comp);

            $manager->persist($comp);

       }
        $manager->flush();

    }

}