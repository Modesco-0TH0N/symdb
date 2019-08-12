<?php


namespace AppBundle\Entity;


use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Exception;


/**
 * @ORM\Entity
 * @ORM\Table(name="payment")
 */
class Payment
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
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Ticker")
     * @ORM\JoinColumn(name="ticker", referencedColumnName="id")
     */
    private $ticker;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=5, nullable=false)
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    private $date;

    /**
     * Transaction constructor.
     * @param $user
     * @param $ticker
     * @param $amount
     * @param string $date
     * @throws Exception
     */
    public function __construct($user, $ticker, $amount = 0, $date = 'now')
    {
        if ($date === 'now') {
            $date = new DateTime();
        }
        $this->setUser($user)->setTicker($ticker)->setAmount($amount)->setDate($date);
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
     * @return Payment
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTicker()
    {
        return $this->ticker;
    }

    /**
     * @param mixed $ticker
     * @return Payment
     */
    public function setTicker($ticker)
    {
        $this->ticker = $ticker;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     * @return Payment
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
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
     * @return Payment
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }
}