<?php


namespace AppBundle\Domain;


use AppBundle\Entity\Balance;
use AppBundle\Entity\Rate;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;

/**
 * Class Exchanger
 * @package AppBundle\Domain
 */
class Exchanger
{
    private $user;

    /**
     * @var EntityManager
     */
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
     * @param Transaction $transaction
     * @return mixed
     * @throws OptimisticLockException
     */
    public function change($transaction)
    {
        $rateRepository = $this->entityManager->getRepository(Rate::class);
        $rate = $rateRepository->findOneBy([
           'ticker1' => $transaction->getTicker1(),
           'ticker2' => $transaction->getTicker2(),
        ]);
        $transaction->setRate($rate);
        $transaction->setAmount2($transaction->getAmount1() * $rate->getPrice());

        $balanceRepository = $this->entityManager->getRepository(Balance::class);

        $balance1 = $balanceRepository->findOneBy([
            'user'   => $this->user,
            'ticker' => $transaction->getTicker1()
        ]);
        $balance1 = $balance1 ? $balance1 : new Balance($this->user, $transaction->getTicker1());
        $balance1->setAmount($balance1->getAmount() - $transaction->getAmount1());

        $balance2 = $balanceRepository->findOneBy([
            'user'   => $this->user,
            'ticker' => $transaction->getTicker2()
        ]);
        $balance2 = $balance2 ? $balance2 : new Balance($this->user, $transaction->getTicker2());
        $balance2->setAmount($balance2->getAmount() + $transaction->getAmount2());

        $this->entityManager->persist($balance1);
        $this->entityManager->persist($balance2);
        $this->entityManager->flush();

        return $transaction;
    }
}