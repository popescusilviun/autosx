<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Marci
 *
 * @ORM\Table(name="marca")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MarcaRepository")
 */
class Marca
{
    /**
     * @var string
     *
     * @ORM\Column(name="nume", type="string", length=255, nullable=false)
     */
    private $nume;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

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
     * @var integer
     *
     * @ORM\Column(name="remote_id", type="smallint", nullable=true)
     */
    private $remoteId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=true)
     */
    private $checked;

    /**
     * Set nume
     *
     * @param string $nume
     * @return Marci
     */
    public function setNume($nume)
    {
        $this->nume = $nume;

        return $this;
    }

    /**
     * Get nume
     *
     * @return string 
     */
    public function getNume()
    {
        return $this->nume;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Marci
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     * @return Marci
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
     *
     * @param boolean $createdAt
     * @return Marci
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get $createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set remoteId
     *
     * @param integer $remoteId
     * @return Marci
     */
    public function setRemoteId($remoteId)
    {
        $this->remoteId = $remoteId;

        return $this;
    }

    /**
     * Get $remoteId
     *
     * @return integer
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Marci
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;

        return $this;
    }

    /**
     * Get $checked
     *
     * @return boolean
     */
    public function getChecked()
    {
        return $this->checked;
    }
}
