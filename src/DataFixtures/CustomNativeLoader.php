<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator as FakerGenerator;
use Nelmio\Alice\Faker\Provider\AliceProvider;
use Nelmio\Alice\Loader\NativeLoader;

class CustomNativeLoader extends NativeLoader
{
    protected function createFakerGenerator(): FakerGenerator
    {
        $generator = Factory::create('fr_FR');
        $generator->addProvider(new AliceProvider());

        return $generator;
    }
}
