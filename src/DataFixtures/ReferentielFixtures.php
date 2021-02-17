<?php

namespace App\DataFixtures;


use App\Entity\Referentiel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ReferentielFixtures extends  Fixture
{
    public const Referentiel = 'referentiel';
    public function load(ObjectManager $manager)
    {
        $faker= Factory::create('fr_FR');

        for($p=0; $p<3; $p++ ){
            $ref = new Referentiel();
            $ref->setLibelle("libelle".$p)
                ->setPresentation("presentation".$p)
                ->setCriteresAdmission("criteresAdmission".$p)
                ->setCriteresEvaluation("criteresEvaluation".$p)
                ->setProgramme($faker->imageUrl(250,250));
                //->addReference(self::Referentiel,$ref);


            $manager->persist($ref);
        }

        $manager->flush();
    }
}
