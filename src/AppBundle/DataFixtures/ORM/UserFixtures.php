<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures implements FixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('User'. random_int(1, 100000));
        $user->setPasswordThroughAllSteps('123456');
        $user->setRole('user');

        $manager->persist($user);
        $manager->flush();
    }
}