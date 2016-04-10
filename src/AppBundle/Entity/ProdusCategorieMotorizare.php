<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ProdusCategorieMotorizare
 *
 * @ORM\Table(name="produs_categorie_motorizare")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProdusCategorieMotorizareRepository")
 */
class ProdusCategorieMotorizare
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produs")
     * @ORM\JoinColumn(name="id_produs", referencedColumnName="id")
     */
    private $produs;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="id_categorie", referencedColumnName="id")
     */
    private $categorie;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Motorizare")
     * @ORM\JoinColumn(name="id_motorizare", referencedColumnName="id")
     */
    private $motorizare;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;


    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set produs
     * @param object $produs
     * @return Produs
     */
    public function setProdus(Produs $produs)
    {
        $this->produs = $produs;
        return $this;
    }

    /**
     * Get produs
     * @return object
     */
    public function getProdus()
    {
        return $this->produs;
    }

    /**
     * Set categorie
     * @param object $categorie
     * @return Produs
     */
    public function setCategorie(Category $categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * Get $categorie
     * @return object
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set motorizare
     * @param object $motorizare
     * @return Produs
     */
    public function setMotorizare(Motorizare $motorizare)
    {
        $this->motorizare = $motorizare;
        return $this;
    }

    /**
     * Get $motorizare
     * @return object
     */
    public function getMotorizare()
    {
        return $this->motorizare;
    }

    /**
     * Set isActive
     * @param boolean $isActive
     * @return Produs
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get $isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     * @param boolean $createdAt
     * @return Produs
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * Get $createdAt
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
