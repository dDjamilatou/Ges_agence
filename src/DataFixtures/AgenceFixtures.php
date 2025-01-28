<?php

namespace App\DataFixtures;

use App\Entity\Agence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgenceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $agencesData = [
            ['numero' => 'AG001', 'adresse' => '123 Rue Principale, Dakar', 'telephone' => '770000001'],
            ['numero' => 'AG002', 'adresse' => '456 Avenue du Marché, Thiès', 'telephone' => '770000002'],
            ['numero' => 'AG003', 'adresse' => '789 Boulevard des Palmiers, Ziguinchor', 'telephone' => '770000003'],
            ['numero' => 'AG004', 'adresse' => '101 Rue des Cocotiers, Saint-Louis', 'telephone' => '770000004'],
            ['numero' => 'AG005', 'adresse' => '202 Avenue de la Plage, Kaolack', 'telephone' => '770000005'],
        ];

       
        foreach ($agencesData as $data) {
            $agence = new Agence();
            $agence->setNumero($data['numero']);
            $agence->setAdresse($data['adresse']);
            $agence->setTelephone($data['telephone']);

            $manager->persist($agence);
        }

    
        $manager->flush();
    }
}
