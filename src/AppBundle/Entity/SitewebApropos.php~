<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SitewebApropos
 *
 * @ORM\Table(name="siteweb_apropos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SitewebAproposRepository")
 */
class SitewebApropos
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
     * @ORM\Column(name="logo", type="text", nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="slogon", type="text", nullable=true)
     */
    private $slogon;

    /**
     * @var string
     *
     * @ORM\Column(name="apropos", type="text", nullable=true)
     */
    private $apropos;

    /**
     * @var \AppBundle\Entity\Siteweb
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Siteweb")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="siteweb", referencedColumnName="id")
     * })
     */
    private $siteweb;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="text", nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_fixe", type="text", nullable=true)
     */
    private $telFixe;

    /**
     * @var string
     *
     * @ORM\Column(name="tel_mobile", type="text", nullable=true)
     */
    private $telMobile;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text", nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook", type="text", nullable=true)
     */
    private $facebook;


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
     * Set logo
     *
     * @param string $logo
     *
     * @return SitewebApropos
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set slogon
     *
     * @param string $slogon
     *
     * @return SitewebApropos
     */
    public function setSlogon($slogon)
    {
        $this->slogon = $slogon;

        return $this;
    }

    /**
     * Get slogon
     *
     * @return string
     */
    public function getSlogon()
    {
        return $this->slogon;
    }

    /**
     * Set apropos
     *
     * @param string $apropos
     *
     * @return SitewebApropos
     */
    public function setApropos($apropos)
    {
        $this->apropos = $apropos;

        return $this;
    }

    /**
     * Get apropos
     *
     * @return string
     */
    public function getApropos()
    {
        return $this->apropos;
    }

    /**
     * Set siteweb
     *
     * @param \AppBundle\Entity\Siteweb $siteweb
     *
     * @return SitewebApropos
     */
    public function setSiteweb(\AppBundle\Entity\Siteweb $siteweb = null)
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    /**
     * Get siteweb
     *
     * @return \AppBundle\Entity\Siteweb
     */
    public function getSiteweb()
    {
        return $this->siteweb;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return SitewebApropos
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return SitewebApropos
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telFixe
     *
     * @param string $telFixe
     *
     * @return SitewebApropos
     */
    public function setTelFixe($telFixe)
    {
        $this->telFixe = $telFixe;

        return $this;
    }

    /**
     * Get telFixe
     *
     * @return string
     */
    public function getTelFixe()
    {
        return $this->telFixe;
    }

    /**
     * Set telMobile
     *
     * @param string $telMobile
     *
     * @return SitewebApropos
     */
    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    /**
     * Get telMobile
     *
     * @return string
     */
    public function getTelMobile()
    {
        return $this->telMobile;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return SitewebApropos
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set facebook
     *
     * @param string $facebook
     *
     * @return SitewebApropos
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string
     */
    public function getFacebook()
    {
        return $this->facebook;
    }
}
