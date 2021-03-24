<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $loader = new CustomNativeLoader();
        $users = $loader->loadFile(__DIR__.'/users.yml')->getObjects();

        // Flush each 20th entry
        $batchSize = 20;

        for ($i = 0; $i < count($users); $i++) {

            if(in_array("ROLE_ADMIN", $users['user_'.$i]->getRoles())){
                $users['user_'.$i]->setAvatar('images/default_admin.jpg');
            } else {
                $users['user_'.$i]->setAvatar('images/default_user.jpg');
            }

            $manager->persist($users['user_'.$i]);

            if(($i % $batchSize) === 0){
                $manager->flush();
                $manager->clear();
            }

        };

        $manager->flush();
        $manager->clear();
    }
}
