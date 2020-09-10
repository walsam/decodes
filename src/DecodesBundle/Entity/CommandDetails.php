<?php

namespace DecodesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CommandDetails
 *
 * @ORM\Table(name="command_details")
 * @ORM\Entity(repositoryClass="DecodesBundle\Repository\CommandDetailsRepository")
 */
class CommandDetails
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var int
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @ORM\ManyToOne(targetEntity="DecodesBundle\Entity\Command",inversedBy="commanddetails")
     * @ORM\JoinColumn(name="command_id", referencedColumnName="id")
     */
    private $command;

    /**
     *
     * @ORM\ManyToOne(targetEntity="DecodesBundle\Entity\Product", inversedBy="commanddetails")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set number
     *
     * @param integer $number
     *
     * @return CommandDetails
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set cost
     *
     * @param integer $cost
     *
     * @return CommandDetails
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return int
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set command
     *
     * @param \DecodesBundle\Entity\Command $command
     *
     * @return CommandDetails
     */
    public function setCommand(\DecodesBundle\Entity\Command $command = null)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return \DecodesBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set produit
     *
     * @param \DecodesBundle\Entity\Product $produit
     *
     * @return CommandDetails
     */
    public function setProduit(\DecodesBundle\Entity\Product $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \DecodesBundle\Entity\Product
     */
    public function getProduit()
    {
        return $this->produit;
    }

    public function __toString() {
        return $this->getProduit()->getName();
    }


}
