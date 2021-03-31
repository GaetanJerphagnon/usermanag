<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new CustomNativeLoader();
        $fixtures = $loader->loadFile(__DIR__.'/appFixtures.yml')->getObjects();
        
        // Flush each 20th entry
        $batchSize = 20;
        $i=1;
        foreach ($fixtures as $fix) {
            $manager->persist($fix);

            if(($i % $batchSize) === 0){
                $manager->flush();
            }
            $i++;

        };

        $manager->flush();
        $manager->clear();
    }
}
