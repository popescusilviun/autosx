<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoryRepository")
 */
class Category
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
     * @var integer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     */
    private $parent;

    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="smallint", nullable=true)
     */
    private $position;

    /**
     * @var integer
     *
     * @ORM\Column(name="level", type="smallint", nullable=true)
     */
    private $level;

    /**
     * Set nume
     * @param string $nume
     * @return Category
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
     * Set slug
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * Get slug
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set isActive
     * @param boolean $isActive
     * @return Category
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * Get $isActive
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set parent
     * @param object $parent
     * @return Category
     */
    public function setParent(Category $parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get $parent
     * @return smallint
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set position
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Get $position
     * @return smallint
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set level
     * @param smallint $level
     * @return Category
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get $level
     * @return smallint
     */
    public function getLevel()
    {
        return $this->level;
    }
}
