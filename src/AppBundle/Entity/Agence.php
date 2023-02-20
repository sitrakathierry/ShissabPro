<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agence
 *
 * @ORM\Table(name="agence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AgenceRepository")
 */
class Agence
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text", nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="text", nullable=true)
     */
    private $region;

    /**
     * @var integer
     *
     * @ORM\Column(name="code", type="integer", nullable=true)
     */
    private $code;

    /**
     * @var integer
     *
     * @ORM\Column(name="capacite", type="integer", nullable=true)
     */
    private $capacite = '0';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="text", nullable=true)
     */
    private $tel;

    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="text", nullable=true)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="devise_symbole", type="text", nullable=true)
     */
    private $deviseSymbole;

    /**
     * @var string
     *
     * @ORM\Column(name="devise_lettre", type="text", nullable=true)
     */
    private $deviseLettre;

    /**
     * @var string
     *
     * @ORM\Column(name="titre_ticket", type="text", nullable=true)
     */
    private $titreTicket = '';

    /**
     * @var string
     *
     * @ORM\Column(name="soustitre_ticket", type="text", nullable=true)
     */
    private $soustitreTicket = '';

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_ticket", type="text", nullable=true)
     */
    private $adresseTicket = '';

    /**
     * @var string
     *
     * @ORM\Column(name="tel_ticket", type="text", nullable=true)
     */
    private $telTicket = '';


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Agence
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return Agence
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return Agence
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return integer
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set capacite
     *
     * @param integer $capacite
     *
     * @return Agence
     */
    public function setCapacite($capacite)
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * Get capacite
     *
     * @return integer
     */
    public function getCapacite()
    {
        return $this->capacite;
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Agence
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
     * Set tel
     *
     * @param string $tel
     *
     * @return Agence
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Agence
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return Agence
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set deviseSymbole
     *
     * @param string $deviseSymbole
     *
     * @return Agence
     */
    public function setDeviseSymbole($deviseSymbole)
    {
        $this->deviseSymbole = $deviseSymbole;

        return $this;
    }

    /**
     * Get deviseSymbole
     *
     * @return string
     */
    public function getDeviseSymbole()
    {
        return $this->deviseSymbole;
    }

    /**
     * Set deviseLettre
     *
     * @param string $deviseLettre
     *
     * @return Agence
     */
    public function setDeviseLettre($deviseLettre)
    {
        $this->deviseLettre = $deviseLettre;

        return $this;
    }

    /**
     * Get deviseLettre
     *
     * @return string
     */
    public function getDeviseLettre()
    {
        return $this->deviseLettre;
    }

    /**
     * Set titreTicket
     *
     * @param string $titreTicket
     *
     * @return Agence
     */
    public function setTitreTicket($titreTicket)
    {
        $this->titreTicket = $titreTicket;

        return $this;
    }

    /**
     * Get titreTicket
     *
     * @return string
     */
    public function getTitreTicket()
    {
        return $this->titreTicket;
    }

    /**
     * Set soustitreTicket
     *
     * @param string $soustitreTicket
     *
     * @return Agence
     */
    public function setSoustitreTicket($soustitreTicket)
    {
        $this->soustitreTicket = $soustitreTicket;

        return $this;
    }

    /**
     * Get soustitreTicket
     *
     * @return string
     */
    public function getSoustitreTicket()
    {
        return $this->soustitreTicket;
    }

    /**
     * Set adresseTicket
     *
     * @param string $adresseTicket
     *
     * @return Agence
     */
    public function setAdresseTicket($adresseTicket)
    {
        $this->adresseTicket = $adresseTicket;

        return $this;
    }

    /**
     * Get adresseTicket
     *
     * @return string
     */
    public function getAdresseTicket()
    {
        return $this->adresseTicket;
    }

    /**
     * Set telTicket
     *
     * @param string $telTicket
     *
     * @return Agence
     */
    public function setTelTicket($telTicket)
    {
        $this->telTicket = $telTicket;

        return $this;
    }

    /**
     * Get telTicket
     *
     * @return string
     */
    public function getTelTicket()
    {
        return $this->telTicket;
    }
}
