<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * ImagineProdus
 *
 * @ORM\Table(name="imagine_produs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImagineProdusRepository")
 */
class ImagineProdus
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
     * @var string
     *
     * @ORM\Column(name="nume", type="string", length=255, nullable=false)
     */
    private $nume;

    /**
     * @var text
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var text
     *
     * @ORM\Column(name="path", type="text", nullable=false)
     */
    private $path;

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
     * Set description
     * @param string $description
     * @return Produs
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set path
     * @param string $path
     * @return Produs
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
