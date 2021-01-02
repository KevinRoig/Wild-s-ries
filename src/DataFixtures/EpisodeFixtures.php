<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use App\Entity\Season;
use ContainerGO6ymyg\getSeasonService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('en_US');
        for($j=0; $j<=5; $j++){
            for($k=1; $k<=8; $k++){
                for ($i = 0; $i < 200; $i++){
                    $episode = new Episode();
                    $episode->setNumber($faker->numberBetween($min=1, $max=22));
                    $episode->setTitle($faker->realText($maxNbChars = 40, $indexSize = 2));
                    $episode->setSynopsis($faker->realText($maxNbChars = 200, $indexSize = 2));
                    $episode->setSeason($this->getReference('prog_'. $j . '_season_'.$k));
                    
                    $manager->persist($episode);
                }
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [ProgramFixtures::class, SeasonFixtures::class];
    }
}
