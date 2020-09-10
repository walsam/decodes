<?php

namespace DecodesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Review
 *
 * @ORM\Table(name="review")
 * @ORM\Entity(repositoryClass="DecodesBundle\Repository\ReviewRepository")
 */
class Review
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
     * @ORM\Column(name="review", type="integer")
     */
    private $review;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     *
     * @ORM\ManyToOne(targetEntity="DecodesBundle\Entity\Client", inversedBy="review")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     *
     * @ORM\ManyToOne(targetEntity="DecodesBundle\Entity\Product", inversedBy="review")
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
     * Set review
     *
     * @param integer $review
     *
     * @return Review
     */
    public function setReview($review)
    {
        $this->review = $review;

        return $this;
    }

    /**
     * Get review
     *
     * @return int
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Review
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set client
     *
     * @param \DecodesBundle\Entity\Client $client
     *
     * @return Review
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
     * Set produit
     *
     * @param \DecodesBundle\Entity\Product $produit
     *
     * @return Review
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
        return $this->description;
    }
}
