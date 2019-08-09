<?php


namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity
 * @ORM\Table(name="rate")
 */
class Rate
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Ticker")
     * @ORM\JoinColumn(name="ticker_1", referencedColumnName="id")
     */
    private $ticker1;

    /**
     * @ORM\JoinColumn(nullable=false)
     * @ORM\ManyToOne(targetEntity="Ticker")
     * @ORM\JoinColumn(name="ticker_2", referencedColumnName="id")
     */
    private $ticker2;

    /**
     * @ORM\Column(type="decimal", scale=5)
     */
    private $price;

    /**
     * Rate constructor.
     * @param $ticker1
     * @param $ticker2
     */
    public function __construct($ticker1, $ticker2)
    {
        $this->setTicker1($ticker1)->setTicker2($ticker2);
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
    public function getTicker1()
    {
        return $this->ticker1;
    }

    /**
     * @param mixed $ticker1
     * @return Rate
     */
    protected function setTicker1($ticker1)
    {
        $this->ticker1 = $ticker1;
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
     * @return Rate
     */
    protected function setTicker2($ticker2)
    {
        $this->ticker2 = $ticker2;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return Rate
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

}