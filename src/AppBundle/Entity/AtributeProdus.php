<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * AtributeProdus
 *
 * @ORM\Table(name="atribute_produs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AtributeProdusRepository")
 */
class AtributeProdus
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produs", inversedBy="atribute")
     * @ORM\JoinColumn(name="id_produs", referencedColumnName="id")
     */
    private $produs;

    /**
     * @var string
     *
     * @ORM\Column(name="nume", type="string", length=255, nullable=false)
     */
    private $nume;

    /**
     * @var text
     *
     * @ORM\Column(name="valoare", type="text", nullable=true)
     */
    private $valoare;

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
     * Set nume
     * @param string $nume
     * @return Produs
     */
    public function setNume($nume)
    {
        $this->nume = $nume;

        return $this;
    }

    /**
     * Get nume
     * @return string
     */
    public function getNume()
    {
        return $this->nume;
    }

    /**
     * Set valoare
     * @param text valoare
     * @return Produs
     */
    public function setValoare($valoare)
    {
        $this->valoare = $valoare;
        return $this;
    }

    /**
     * Get valoare
     * @return string
     */
    public function getValoare()
    {
        return $this->valoare;
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
