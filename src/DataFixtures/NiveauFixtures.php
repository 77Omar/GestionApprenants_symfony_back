<?php

namespace App\DataFixtures;
use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {

        for ($c = 1; $c <= 3; $c++) {
            $niveau = new Niveau();
            $niveau->setLibelle('Niveau'.$c);

            $niveau->setCritereEvaluation("Critere d'evaluation".$c);

            $niveau->setGroupeAction("Groupe d'action".$c);

            $niveau->setCompetence($this->getReference(CompetencesFixtures:: competence));

            $manager->persist($niveau);

        }
        $manager->flush();

    }
}