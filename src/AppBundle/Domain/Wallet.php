<?php


namespace AppBundle\Domain;


use AppBundle\Entity\Balance;
use AppBundle\Entity\Ticker;


/**
 * Class Wallet
 * @package AppBundle\Domain
 */
class Wallet
{
    private $user;
    private $entityManager;

    /**
     * Wallet constructor.
     * @param $user
     * @param $entityManager
     */
    public function __construct($user, $entityManager)
    {
        $this->user = $user;
        $this->entityManager = $entityManager;
    }

    /**
     * @return array
     */
    public function getCurrencies()
    {
        $balances = [];

        $tickerRepository = $this->entityManager->getRepository(Ticker::class);
        $tickers = $tickerRepository->findAll();
        foreach ($tickers as $ticker) {
            $tick = $ticker->getName();
            $balances[$tick] = 0;
        }

        $balanceRepository = $this->entityManager->getRepository(Balance::class);
        $currencies = $balanceRepository->findBy(['user' => $this->user]);
        foreach ($currencies as $currency) {
            $ticker = $currency->getTicker()->getName();
            $balances[$ticker] = $currency->getAmount();
        }

        return $balances;
    }


    public function charge($payment)
    {
        $balanceRepository = $this->entityManager->getRepository(Balance::class);
        $balance = $balanceRepository->findOneBy([
            'user' => $this->user,
            'ticker' => $payment->getTicker()
        ]);
        $balance = $balance ? $balance : new Balance($this->user, $payment->getTicker());
        $balance->setAmount($balance->getAmount() + $payment->getAmount());
        $this->entityManager->persist($balance);
        $this->entityManager->flush();
    }
}