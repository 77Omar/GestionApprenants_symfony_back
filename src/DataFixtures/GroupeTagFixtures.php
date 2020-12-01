<?php

namespace App\DataFixtures;
use App\Entity\GroupeTag;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class GroupeTagFixtures extends Fixture implements DependentFixtureInterface
{
    public const group = 'groupTag';

    public function load(ObjectManager $manager)
    {
        $tab= ['DataScientist','Developpeur','frontend','backend'];

        for ($i = 0; $i < count($tab); $i++) {
            $grpTag= new GroupeTag();
                    $grpTag->setLibelle($tab[$i]);
                    $grpTag->addTag($this->getReference(TagFixtures::tagGroup.$i));

            $manager->persist($grpTag);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // TODO: Implement getDependencies() method.
        return array(
            TagFixtures::class
        );
    }
}
