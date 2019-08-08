<?php


namespace AppBundle\Domain;


use AppBundle\Entity\Balance;
use AppBundle\Entity\Ticker;


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
        $balances = [];

        $tickerRepository = $this->entityManager->getRepository(Ticker::class);
        $tickers = $tickerRepository->findAll();
        foreach ($tickers as $ticker) {
            $tick = $ticker->getDescription();
            $balances[$tick] = 0;
        }

        $balanceRepository = $this->entityManager->getRepository(Balance::class);
        $currencies = $balanceRepository->findBy(['user' => $this->user]);
        foreach ($currencies as $currency) {
            $ticker = $currency->getTicker()->getDescription();
            $balances[$ticker] = $currency->getAmount();
        }

        return $balances;
    }
}