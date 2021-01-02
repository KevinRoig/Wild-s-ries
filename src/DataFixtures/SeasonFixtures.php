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
        $a=0;
        foreach (ProgramFixtures::PROGRAMS as $prog){
            for ($i = 1; $i <= 8; $i++){
                $season = new Season();
                $season->setNumber($i);
                $season->setYear($faker->numberBetween($min=1980, $max=2020));
                $season->setDescription($faker->realText($maxNbChars = 200, $indexSize = 2));
                $season->setProgram($this->getReference('prog_'. $a));

                $manager->persist($season);    
                $this->setReference('prog_' . $a . '_season_'.$i, $season);
            }$a++;
        $manager->flush();
    }
}

    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }
}
