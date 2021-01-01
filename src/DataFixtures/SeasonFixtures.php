<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for ($i = 1; $i <= 50; $i++){
            $season = new Season();
            $season->setNumber($faker->numberBetween($min=1, $max=8));
            $season->setYear($faker->numberBetween($min=1980, $max=2020));
            $season->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
            $season->setProgram($this->getReference('prog_'. array_rand(ProgramFixtures::PROGRAMS)));

            $manager->persist($season);    
            $this->setReference('season_'.$i, $season);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
