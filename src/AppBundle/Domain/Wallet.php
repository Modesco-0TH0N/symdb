<?php


namespace AppBundle\Domain;


use AppBundle\Entity\Balance;
use AppBundle\Entity\Payment;
use AppBundle\Entity\Ticker;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;


/**
 * Class Wallet
 * @package AppBundle\Domain
 */
class Wallet
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * Wallet constructor.
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getCurrencies(User $user): array
    {
        $balances = [];

        $tickerRepository = $this->entityManager->getRepository(Ticker::class);
        $tickers = $tickerRepository->findAll();
        foreach ($tickers as $ticker) {
            /**
             * @var Ticker $ticker
             */
            $tick = $ticker->getName();
            $balances[$tick] = 0;
        }

        $balanceRepository = $this->entityManager->getRepository(Balance::class);
        $currencies = $balanceRepository->findBy(['user' => $user]);
        foreach ($currencies as $currency) {
            /**
             * @var Balance $currency
             */
            $ticker = $currency->getTicker()->getName();
            $balances[$ticker] = $currency->getAmount();
        }

        return $balances;
    }

    /**
     * @param User $user
     * @param Payment $payment
     * @throws OptimisticLockException
     */
    public function charge(User $user, Payment $payment)
    {
        $balanceRepository = $this->entityManager->getRepository(Balance::class);
        $balance = $balanceRepository->findOneBy([
            'user' => $user,
            'ticker' => $payment->getTicker()
        ]);
        $balance = $balance ? $balance : new Balance($user, $payment->getTicker());
        $balance->setAmount($balance->getAmount() + $payment->getAmount());
        $this->entityManager->persist($balance);
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }
}