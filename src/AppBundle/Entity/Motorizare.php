<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Marci
 *
 * @ORM\Table(name="motorizare")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MotorizareRepository")
 */
class Motorizare
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
     * @var string
     *
     * @ORM\Column(name="subtitlu", type="text", nullable=true)
     */
    private $subtitlu;

    /**
     * @var string
     *
     * @ORM\Column(name="descriere", type="text", nullable=true)
     */
    private $descriere;

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
     * @ORM\Column(name="an_start", type="smallint", nullable=true)
     */
    private $anStart;

    /**
     * @var integer
     *
     * @ORM\Column(name="an_final", type="smallint", nullable=true)
     */
    private $anFinal;

    /**
     * @var integer
     *
     * @ORM\Column(name="kw", type="smallint", nullable=true)
     */
    private $kw;

    /**
     * @var integer
     *
     * @ORM\Column(name="cp", type="smallint", nullable=true)
     */
    private $cp;

    /**
     * @var integer
     *
     * @ORM\Column(name="cmc", type="smallint", nullable=true)
     */
    private $cmc;

    /**
     * @var decimal
     * @ORM\Column(name="litri", type="decimal", precision=5, scale=2, nullable=true)
     */
    private $litri;

    /**
     * @var string
     *
     * @ORM\Column(name="caroserie", type="string", length=50, nullable=true)
     */
    private $caroserie;

    /**
     * @var string
     *
     * @ORM\Column(name="cod_motor", type="string", length=255, nullable=true)
     */
    private $codMotor;

    /**
     * @var string
     *
     * @ORM\Column(name="carburant", type="string", length=50, nullable=true)
     */
    private $carburant;

    /**
     * @var integer
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Model")
     * @ORM\JoinColumn(name="id_model", referencedColumnName="id")
     */
    private $model;

    /**
     * Set nume
     * @param string $nume
     * @return Motorizare
     */
    public function setNume($nume) { $this->nume = $nume; return $this; }
    /**
     * Get nume
     * @return string
     */
    public function getNume() { return $this->nume; }

    /**
     * Set slug
     * @param string $slug
     * @return Motorizare
     */
    public function setSlug($slug) { $this->slug = $slug; return $this; }
    /**
     * Get slug
     * @return string
     */
    public function getSlug() { return $this->slug; }

    /**
     * Set subtitlu
     *
     * @param string $subtitlu
     * @return Motorizare
     */
    public function setSubtitlu($subtitlu)
    {
        $this->subtitlu = $subtitlu;

        return $this;
    }

    /**
     * Get subtitlu
     *
     * @return string 
     */
    public function getSubtitlu()
    {
        return $this->subtitlu;
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
     * Set descriere
     *
     * @param text $descriere
     * @return Motorizare
     */
    public function setDescriere($descriere)
    {
        $this->descriere = $descriere;

        return $this;
    }

    /**
     * Get $descriere
     *
     * @return text
     */
    public function getDescriere()
    {
        return $this->descriere;
    }

    /**
     * Set model
     *
     * @param integer $model
     * @return Motorizare
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return integer
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set anStart
     *
     * @param integer $anStart
     * @return Motorizare
     */
    public function setAnStart($anStart)
    {
        $this->anStart = $anStart;

        return $this;
    }

    /**
     * Get $anStart
     *
     * @return integer
     */
    public function getAnStart()
    {
        return $this->anStart;
    }

    /**
     * Set anFinal
     * @param integer $anFinal
     * @return Motorizare
     */
    public function setAnFinal($anFinal) { $this->anFinal = $anFinal; return $this; }
    /**
     * Get $anFinal
     *
     * @return integer
     */
    public function getAnFinal() { return $this->anFinal; }

    /**
     * Set kw
     * @param integer $kw
     * @return Motorizare
     */
    public function setKw($kw) { $this->kw = $kw; return $this; }
    /**
     * Get $kw
     * @return integer
     */
    public function getKw() { return $this->kw; }

    /**
     * Set cp
     * @param integer $cp
     * @return Motorizare
     */
    public function setCp($cp) { $this->cp = $cp; return $this; }
    /**
     * Get $cp
     * @return integer
     */
    public function getCp() { return $this->cp; }

    /**
     * Set cmc
     * @param integer $cmc
     * @return Motorizare
     */
    public function setCmc($cmc) { $this->cmc = $cmc; return $this; }
    /**
     * Get cmc
     * @return integer
     */
    public function getCmc() { return $this->cmc; }

    /**
     * Set litri
     * @param decimal $litri
     * @return Motorizare
     */
    public function setLitri($litri) { $this->litri = $litri; return $this; }
    /**
     * Get litri
     * @return decimal
     */
    public function getLitri() { return $this->litri; }

    /**
     * Set caroserie
     * @param string $caroserie
     * @return Motorizare
     */
    public function setCaroserie($caroserie) { $this->caroserie = $caroserie; return $this; }
    /**
     * Get caroserie
     * @return string
     */
    public function getCaroserie() { return $this->caroserie; }

    /**
     * Set codMotor
     * @param string $codMotor
     * @return Motorizare
     */
    public function setCodMotor($codMotor) { $this->codMotor = $codMotor; return $this; }
    /**
     * Get codMotor
     * @return string
     */
    public function getCodMotor() { return $this->codMotor; }

    /**
     * Set carburant
     * @param string $carburant
     * @return Motorizare
     */
    public function setCarburant($carburant) { $this->carburant = $carburant; return $this; }
    /**
     * Get carburant
     * @return string
     */
    public function getCarburant() { return $this->carburant; }
}
