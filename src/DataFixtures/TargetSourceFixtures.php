<?php

namespace App\DataFixtures;

use App\Entity\TargetSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TargetSourceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $targetSources = [
            [
                'confidenceLevel' => 1,
                'domain' => 'lci.fr'
            ],
            [
                'confidenceLevel' => 1,
                'domain' => 'lemonde.fr'
            ],
            [
                'confidenceLevel' => 1,
                'domain' => 'lefigaro.fr'
            ],
            [
                'confidenceLevel' => 1,
                'domain' => 'leparisien.fr'
            ],
            [
                'confidenceLevel' => 1,
                'domain' => '20minutes.fr'
            ],
            [
                'confidenceLevel' => 1,
                'domain' => 'francetvinfo.fr'
            ],
            [
                'confidenceLevel' => 2,
                'domain' => 'bfmtv.com'
            ],
            [
                'confidenceLevel' => 4,
                'domain' => 'legorafi.fr'
            ],
        ];

        foreach ($targetSources as $source) {
            $targetSource =  new TargetSource();
            $targetSource->setConfidenceLevel($source['confidenceLevel']);
            $targetSource->setDomain($source['domain']);

            $manager->persist($targetSource);

        }

        $manager->flush();

    }

}
