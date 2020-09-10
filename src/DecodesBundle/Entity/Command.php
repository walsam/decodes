<?php

namespace DecodesBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="DecodesBundle\Repository\CommandRepository")
 */
class Command
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmation", type="boolean")
     */
    private $confirmation;

    /**
     * @ORM\ManyToOne(targetEntity="DecodesBundle\Entity\Client", inversedBy="command")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     *
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="DecodesBundle\Entity\CommandDetails", mappedBy="command")
     */
    private $commanddetails;

    public function __construct()
    {
        $this->commanddetails = new ArrayCollection();
    }

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Command
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set confirmation
     *
     * @param boolean $confirmation
     *
     * @return Command
     */
    public function setConfirmation($confirmation)
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    /**
     * Get confirmation
     *
     * @return bool
     */
    public function getConfirmation()
    {
        return $this->confirmation;
    }

    /**
     * Set client
     *
     * @param \DecodesBundle\Entity\Client $client
     *
     * @return Command
     */
    public function setClient(\DecodesBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \DecodesBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Add commanddetail
     *
     * @param \DecodesBundle\Entity\CommandDetails $commanddetail
     *
     * @return Command
     */
    public function addCommanddetail(\DecodesBundle\Entity\CommandDetails $commanddetail)
    {
        $this->commanddetails[] = $commanddetail;

        return $this;
    }

    /**
     * Remove commanddetail
     *
     * @param \DecodesBundle\Entity\CommandDetails $commanddetail
     */
    public function removeCommanddetail(\DecodesBundle\Entity\CommandDetails $commanddetail)
    {
        $this->commanddetails->removeElement($commanddetail);
    }

    /**
     * Get commanddetails
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommanddetails()
    {
        return $this->commanddetails;
    }

    public function __toString() {
        return $this->getClient()->getLastname();
    }
}
