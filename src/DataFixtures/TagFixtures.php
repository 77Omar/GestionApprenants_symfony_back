<?php

namespace App\DataFixtures;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public const tagGroup = 'tag';

    public function load(ObjectManager $manager)
    {
        $tabs= ['php','angular','symfony','js'];
        for ($p = 0; $p < count($tabs); $p++) {
            $tags = new Tag();
            $tags->setLibelle($tabs[$p]);
            $this->addReference(self::tagGroup.$p, $tags);
            $manager->persist($tags);
        }
        $manager->flush();
    }
}
