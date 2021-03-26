<?php

namespace App\Tests;

use App\Entity\User;
use App\Service\Slugger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SluggerTest extends KernelTestCase
{
    public function testValidEntity()
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());

        $admin = self::$container->get('doctrine')->getRepository(User::class)->findOneByEmail(['email', 'admin@gmail.com']);
        $slugger = self::$container->get(Slugger::class);
        $slug = $slugger->slugifyUser($admin)->getSlug();

        $validSlug = strtolower($admin->getFirstname().'-'.$admin->getLastname().'-'.$admin->getId());
        $this->assertSame($validSlug,$slug);
    }

    public function testInvalidEntity()
    {
        $kernel = self::bootKernel();
        $this->assertSame('test', $kernel->getEnvironment());

        $user = new User();
        $user->setFirstname('Malcolm')
                ->setLastname('Nolastname');
        $slugger = self::$container->get(Slugger::class);
        $slug = $slugger->slugifyUser($user)->getSlug();

        $validSlug = strtolower($user->getFirstname().'-'.$user->getLastname().'-'.$user->getId());
        $this->assertNotSame($validSlug,$slug);
    }
}
