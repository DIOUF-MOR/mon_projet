<?php

namespace App\DataFixtures;

use App\Entity\Agence;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AgenceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <=10; $i++) {
            $agence = new Agence();
            $agence->setNumero('AG'.$i);
            $agence->setTelephone('33800101'.$i);
            $agence->setAdresse('Dakar'.$i);
            $manager->persist($agence);
            $this->addReference("Agence".$i, $agence);
            }
            $manager->flush();
    }
}
