<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Produs
 *
 * @ORM\Table(name="produs")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProdusRepository")
 */
class Produs
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Producator")
     * @ORM\JoinColumn(name="id_producator", referencedColumnName="id")
     */
    private $producator;

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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, nullable=false)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_produs_intern", type="string", length=100, nullable=true)
     */
    private $codProdusIntern;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_produs", type="string", length=100, nullable=true)
     */
    private $codProdus;

    /**
     * @var decimal
     * @ORM\Column(name="pret", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $pret;

    /**
     * @var decimal
     * @ORM\Column(name="pret_vechi", type="decimal", precision=7, scale=2, nullable=true)
     */
    private $pretVechi;

    /**
     * @var smallint
     * @ORM\Column(name="discount", type="smallint", nullable=true)
     */
    private $discount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="piesa_veche", type="boolean", nullable=true)
     */
    private $piesaVeche;

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
     * @ORM\OneToMany(targetEntity="AtributeProdus", mappedBy="produs", cascade={"persist", "remove"})
     * @ORM\OrderBy({"id" = "ASC"})
     */
    protected $atribute;

    /**
     * Get id
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set producator
     * @param object $producator
     * @return Produs
     */
    public function setProducator(Producator $producator)
    {
        $this->producator = $producator;
        return $this;
    }

    /**
     * Get producator
     * @return object
     */
    public function getProducator()
    {
        return $this->producator;
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
     * Set slug
     * @param string $slug
     * @return Produs
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
     * Set codProdusIntern
     * @param string $codProdusIntern
     * @return Produs
     */
    public function setCodProdusIntern($codProdusIntern)
    {
        $this->codProdusIntern = $codProdusIntern;
        return $this;
    }

    /**
     * Get codProdusIntern
     * @return string
     */
    public function getCodProdusIntern()
    {
        return $this->codProdusIntern;
    }

    /**
     * Set codProdus
     * @param string $codProdus
     * @return Produs
     */
    public function setCodProdus($codProdus)
    {
        $this->codProdus = $codProdus;
        return $this;
    }

    /**
     * Get codProdus
     * @return string
     */
    public function getCodProdus()
    {
        return $this->codProdus;
    }

    /**
     * Set pret
     * @param string $pret
     * @return Produs
     */
    public function setPret($pret)
    {
        $this->pret = $pret;
        return $this;
    }

    /**
     * Get pret
     * @return string
     */
    public function getPret()
    {
        return $this->pret;
    }

    /**
     * Set pretVechi
     * @param string $pretVechi
     * @return Produs
     */
    public function setPretVechi($pretVechi)
    {
        $this->pretVechi = $pretVechi;
        return $this;
    }

    /**
     * Get pretVechi
     * @return string
     */
    public function getPretVechi()
    {
        return $this->pretVechi;
    }

    /**
     * Set discount
     * @param string $discount
     * @return Produs
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * Get discount
     * @return string
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * Set piesaVeche
     * @param string $piesaVeche
     * @return Produs
     */
    public function setPiesaVeche($piesaVeche)
    {
        $this->piesaVeche = $piesaVeche;
        return $this;
    }

    /**
     * Get piesaVeche
     * @return string
     */
    public function getPiesaVeche()
    {
        return $this->piesaVeche;
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

    public function getAtribute() {
        return $this->atribute;
    }
}
