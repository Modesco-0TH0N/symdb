<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Exception;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="transaction")
 */
class Transaction
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Ticker")
     * @ORM\JoinColumn(name="ticker_1", referencedColumnName="id")
     */
    private $ticker1;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=5, nullable=false)
     */
    private $amount1;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Rate")
     * @ORM\JoinColumn(name="rate", referencedColumnName="id")
     */
    private $rate;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Ticker")
     * @ORM\JoinColumn(name="ticker_2", referencedColumnName="id")
     */
    private $ticker2;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=5, nullable=false)
     */
    private $amount2;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * Transaction constructor.
     * @param $user
     * @param $ticker1
     * @param $amount1
     * @param $rate
     * @param $ticker2
     * @param $amount2
     * @param string $date
     * @throws Exception
     */
    public function __construct($user, $ticker1, $amount1 = null, $rate = null,
                                $ticker2 = null, $amount2 = null, $date = 'now')
    {
        if ($date === 'now') {
            $date = new \DateTime();
        }

        $this->setUser($user)->setTicker1($ticker1)->setAmount1($amount1)
            ->setRate($rate)->setTicker2($ticker2)->setAmount2($amount2)->setDate($date);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Transaction
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicker1()
    {
        return $this->ticker1;
    }

    /**
     * @param mixed $ticker1
     * @return Transaction
     */
    public function setTicker1($ticker1)
    {
        $this->ticker1 = $ticker1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount1()
    {
        return $this->amount1;
    }

    /**
     * @param mixed $amount1
     * @return Transaction
     */
    public function setAmount1($amount1)
    {
        $this->amount1 = $amount1;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     * @return Transaction
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicker2()
    {
        return $this->ticker2;
    }

    /**
     * @param mixed $ticker2
     * @return Transaction
     */
    public function setTicker2($ticker2)
    {
        $this->ticker2 = $ticker2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount2()
    {
        return $this->amount2;
    }

    /**
     * @param mixed $amount2
     * @return Transaction
     */
    public function setAmount2($amount2)
    {
        $this->amount2 = $amount2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     * @return Transaction
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
}