<?php

namespace App\DataFixtures;

use App\Entity\TargetSource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TargetSourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $targetSources = [
            [
                'confidenceLevel' => 4,
                'domain' => 'lci.fr'
            ],
            [
                'confidenceLevel' => 4,
                'domain' => 'lemonde.fr'
            ],
            [
                'confidenceLevel' => 4,
                'domain' => 'lefigaro.fr'
            ],
            [
                'confidenceLevel' => 4,
                'domain' => 'lefigaro.fr'
            ],
            [
                'confidenceLevel' => 4,
                'domain' => '20minutes.fr'
            ],
            [
                'confidenceLevel' => 4,
                'domain' => 'francetvinfo.fr'
            ],
            [
                'confidenceLevel' => 3,
                'domain' => 'bfmtv.com'
            ],
            [
                'confidenceLevel' => 1,
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
