<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\UniqueConstraint;


/**
 * @ORM\Entity
 * @ORM\Table(name="balance", uniqueConstraints={@UniqueConstraint(name="search_idx", columns={"user_id", "ticker_id"})})
 */
class Balance
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="User", inversedBy="balances")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Ticker", inversedBy="balances")
     * @ORM\JoinColumn(name="ticker_id", referencedColumnName="id")
     */
    private $ticker;

    /**
     * @ORM\Column(type="decimal", precision=20, scale=5)
     */
    private $amount;

    /**
     * Balance constructor.
     * @param $user
     * @param $ticker
     */
    public function __construct($user, $ticker)
    {
        $this->setUser($user)->setTicker($ticker);
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
     * @return Balance
     */

    protected function setUser($user)
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
     * @return Balance
     */
    protected function setTicker($ticker)
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
     * @return Balance
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }
}