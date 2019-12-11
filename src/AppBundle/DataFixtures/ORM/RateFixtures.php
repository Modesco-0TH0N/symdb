<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Rate;
use AppBundle\Entity\Ticker;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RateFixtures implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tickerRepository = $manager->getRepository(Ticker::class);
        $usdId = $tickerRepository->findOneBy(["name" => 'usd']);
        $euId = $tickerRepository->findOneBy(["name" => 'eu']);
        $rubId = $tickerRepository->findOneBy(["name" => 'rub']);

        $usdToEuRate = new Rate($usdId, $euId);
        $usdToEuRate->setPrice(0.91);
        $manager->persist($usdToEuRate);

        $usdToRubRate = new Rate($usdId, $rubId);
        $usdToRubRate->setPrice(66.16);
        $manager->persist($usdToRubRate);

        $euToUsdRate = new Rate($euId, $usdId);
        $euToUsdRate->setPrice(1.098901099);
        $manager->persist($euToUsdRate);

        $euToRubRate = new Rate($euId, $rubId);
        $euToRubRate->setPrice(72.99);
        $manager->persist($euToRubRate);

        $rubToUsdRate = new Rate($rubId, $usdId);
        $rubToUsdRate->setPrice(0.015114873);
        $manager->persist($rubToUsdRate);

        $rubToEuRate = new Rate($rubId, $euId);
        $rubToEuRate->setPrice(0.013700507);
        $manager->persist($rubToEuRate);

        $manager->flush();
    }
}