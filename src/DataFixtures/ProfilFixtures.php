<?php

namespace App\DataFixtures;
use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfilFixtures extends Fixture
{
    public const Profil_User = 'user';

    public function load(ObjectManager $manager)
    {
        $tab= ['admin','apprenant','formateur','cm'];
        for ($i = 0; $i < count($tab); $i++) {
            $profil = new Profil();
            $profil->setLibelle($tab[$i]);
            $this->addReference(self::Profil_User.$i, $profil);
            $manager->persist($profil);
        }
        $manager->flush();
    }
}
