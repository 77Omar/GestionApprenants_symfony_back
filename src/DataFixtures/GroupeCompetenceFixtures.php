<?php

namespace App\DataFixtures;
use App\Entity\GroupeCompetences;
use App\Entity\Competences;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupeCompetenceFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $tab= ['Developpement web','Gestion projet', 'DataScientist'];

        for ($i = 0; $i < count($tab); $i++) {
            $grpCompetence= new GroupeCompetences();
            $grpCompetence->setLibelle($tab[$i]);
            $grpCompetence->setDescriptif('description developpement web');
            $grpCompetence->addCompetence($this->getReference(CompetencesFixtures::competence.$i));

            $manager->persist($grpCompetence);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return array(
            CompetencesFixtures::class
        );
    }
}
