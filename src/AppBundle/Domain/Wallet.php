<?php


namespace AppBundle\Domain;


use AppBundle\Entity\Balance;


class Wallet
{
    private $user;
    private $entityManager;

    public function __construct($user, $entityManager)
    {
        $this->user = $user;
        $this->entityManager = $entityManager;
    }

    public function getCurrencies()
    {
        $balanceRepository = $this->entityManager->getRepository(Balance::class);
        return $balanceRepository->findBy(['user' => $this->user]);
    }
}