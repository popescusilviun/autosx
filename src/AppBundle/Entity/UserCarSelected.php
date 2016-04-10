<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * UserCarSelected
 *
 * @ORM\Table(name="user_car_selected")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserCarSelectedRepository")
 */
class UserCarSelected
{
    /**
     * @var string
     *
     * @ORM\Column(name="cookie", type="string", length=50, nullable=true)
     */
    private $cookie;

    /**
     * @var text
     *
     * @ORM\Column(name="car_data", type="text", nullable=false)
     */
    private $carData;

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
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    private $updatedAt;

    /**
     * Set cookie
     * @param string $cookie
     * @return UserCarSelected
     */
    public function setCookie($cookie)
    {
        $this->cookie = $cookie;
        return $this;
    }

    /**
     * Get cookie
     * @return string
     */
    public function getCookie()
    {
        return $this->cookie;
    }

    /**
     * Set carData
     * @param text $carData
     * @return UserCarSelected
     */
    public function setCarData($carData)
    {
        $this->carData = $carData;
        return $this;
    }

    /**
     * Get carData
     * @return text
     */
    public function getCarData()
    {
        return $this->carData;
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
     * Set idUser
     * @param integer $idUser
     * @return UserCarSelected
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
        return $this;
    }

    /**
     * Get $idUser
     * @return integer
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set createdAt
     * @param \DateTime $createdAt
     * @return UserCarSelected
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
     * Set updatedAt
     * @param \DateTime $updatedAt
     * @return UserCarSelected
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    /**
     * Get $updatedAt
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

}