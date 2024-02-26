<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeanceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // On supposera que le début des séances peuvent être compris entre 9h30 et 23h30
        $faker = Factory::create();
        $startHour = 9;
        $endHour = 23;
        $minPrice = 7;
        $maxPrice = 25;
        $discount = 15;
        $nombreDeFilms = $manager->getRepository(Film::class)->count([]);
        $nombreDeSalles = $manager->getRepository(Salle::class)->count([]);
        for($i = 0; $i < 200; $i++){
            $seance = new Seance();
            $date = $faker->dateTimeBetween("now", "+1 week");
            $hour = mt_rand($startHour, $endHour);
            $minute = mt_rand(0,3) * 15;
            if($hour == $startHour){
                $minute = mt_rand(2,3) * 15;
            }
            if($hour == $endHour && $minute > 30){
                $minute = 30;
            }
            $date->setTime($hour,$minute);
            $seance->setDateProjection($date);
            $seance->setTarifNormal(mt_rand($minPrice, $maxPrice));
            $seance->setTarifReduit($seance->getTarifNormal()*((100 - $discount)/100));
            $film = $this->getReference("film-".mt_rand(0,$nombreDeFilms - 1));
            $seance->setFilm($film);
            $salle = $this->getReference("salle-".mt_rand(0,$nombreDeSalles-1));
            $seance->setSalle($salle);
            $manager->persist($seance);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            FilmFixtures::class,
            SalleFixtures::class,
        ];
    }
}
