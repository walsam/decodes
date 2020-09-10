<?php

namespace DecodesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="DecodesBundle\Repository\ProductRepository")
 */
class Product
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product image as a jpg file.")
     * @Assert\Image(
     *     allowLandscape = true,
     *     allowPortrait = true
     * )
     */
    private $picture;

    /**
     * @var float
     *
     * @ORM\Column(name="promoprice", type="float")
     */
    private $promoprice;

    /**
     *
     * @ORM\ManyToOne(targetEntity="DecodesBundle\Entity\Category", inversedBy="produit")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;

    /**
     *
     * @ORM\OneToMany(targetEntity="DecodesBundle\Entity\Review", mappedBy="produit")
     */
    private $review;

    /**
     *
     * @ORM\OneToMany(targetEntity="DecodesBundle\Entity\CommandDetails", mappedBy="produit")
     */
    private $commanddetails;

    public function __construct() {
        $this->review = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commanddetails = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
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
     * Set price
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set picture
     *
     * @param string $picture
     *
     * @return Product
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set promoprice
     *
     * @param float $promoprice
     *
     * @return Product
     */
    public function setPromoprice($promoprice)
    {
        $this->promoprice = $promoprice;

        return $this;
    }

    /**
     * Get promoprice
     *
     * @return float
     */
    public function getPromoprice()
    {
        return $this->promoprice;
    }

    /**
     * Set categorie
     *
     * @param \DecodesBundle\Entity\Category $categorie
     *
     * @return Product
     */
    public function setCategorie(\DecodesBundle\Entity\Category $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \DecodesBundle\Entity\Category
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Add review
     *
     * @param \DecodesBundle\Entity\Review $review
     *
     * @return Product
     */
    public function addReview(\DecodesBundle\Entity\Review $review)
    {
        $this->review[] = $review;

        return $this;
    }

    /**
     * Remove review
     *
     * @param \DecodesBundle\Entity\Review $review
     */
    public function removeReview(\DecodesBundle\Entity\Review $review)
    {
        $this->review->removeElement($review);
    }

    /**
     * Get review
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * Add commanddetail
     *
     * @param \DecodesBundle\Entity\CommandDetails $commanddetail
     *
     * @return Product
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
        return $this->name;
    }
}
