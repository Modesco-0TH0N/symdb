<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Ticker;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TickerFixtures implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $usdTicker = new Ticker('usd', 'US Dollar');
        $manager->persist($usdTicker);
        $euroTicker = new Ticker('eu', 'Euro');
        $manager->persist($euroTicker);
        $rubleTicker = new Ticker('rub', 'Russian ruble');
        $manager->persist($rubleTicker);

        $manager->flush();
    }
}