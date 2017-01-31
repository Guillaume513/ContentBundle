<?php

namespace ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Adress
 *
 * @ORM\Table(name="adress")
 * @ORM\Entity(repositoryClass="ContentBundle\Repository\AdressRepository")
 */
class Adress
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
     * @ORM\Column(name="adress", type="text")
     */
    private $adress;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=150, nullable=true)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fixe", type="string", length=15, nullable=true)
     */
    private $fixe;

    /**
     * @var string
     *
     * @ORM\Column(name="gps", type="string", length=255, nullable=true)
     */
    private $gps;


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
     * Set adress
     *
     * @param string $adress
     *
     * @return Adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get adress
     *
     * @return string
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return Adress
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Adress
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Adress
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fixe
     *
     * @param string $fixe
     *
     * @return Adress
     */
    public function setFixe($fixe)
    {
        $this->fixe = $fixe;

        return $this;
    }

    /**
     * Get fixe
     *
     * @return string
     */
    public function getFixe()
    {
        return $this->fixe;
    }

    /**
     * Set gps
     *
     * @param string $gps
     *
     * @return Adress
     */
    public function setGps($gps)
    {
        $this->gps = $gps;

        return $this;
    }

    /**
     * Get gps
     *
     * @return string
     */
    public function getGps()
    {
        return $this->gps;
    }
    
}
