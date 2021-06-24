<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModelePdf
 *
 * @ORM\Table(name="modele_pdf")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModelePdfRepository")
 */
class ModelePdf
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="modele", type="integer")
     */
    private $modele;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_droite", type="text", nullable=true)
     */
    private $logoDroite;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_centre", type="text", nullable=true)
     */
    private $logoCentre;

    /**
     * @var string
     *
     * @ORM\Column(name="logo_gauche", type="text", nullable=true)
     */
    private $logoGauche;

    /**
     * @var string
     *
     * @ORM\Column(name="texte_haut", type="text", nullable=true)
     */
    private $texteHaut;

    /**
     * @var \AppBundle\Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence", referencedColumnName="id")
     * })
     */
    private $agence;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return ModelePdf
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
     * Set modele
     *
     * @param integer $modele
     *
     * @return ModelePdf
     */
    public function setModele($modele)
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return int
     */
    public function getModele()
    {
        return $this->modele;
    }

    /**
     * Set logoDroite
     *
     * @param string $logoDroite
     *
     * @return ModelePdf
     */
    public function setLogoDroite($logoDroite)
    {
        $this->logoDroite = $logoDroite;

        return $this;
    }

    /**
     * Get logoDroite
     *
     * @return string
     */
    public function getLogoDroite()
    {
        return $this->logoDroite;
    }

    /**
     * Set logoCentre
     *
     * @param string $logoCentre
     *
     * @return ModelePdf
     */
    public function setLogoCentre($logoCentre)
    {
        $this->logoCentre = $logoCentre;

        return $this;
    }

    /**
     * Get logoCentre
     *
     * @return string
     */
    public function getLogoCentre()
    {
        return $this->logoCentre;
    }

    /**
     * Set logoGauche
     *
     * @param string $logoGauche
     *
     * @return ModelePdf
     */
    public function setLogoGauche($logoGauche)
    {
        $this->logoGauche = $logoGauche;

        return $this;
    }

    /**
     * Get logoGauche
     *
     * @return string
     */
    public function getLogoGauche()
    {
        return $this->logoGauche;
    }

    /**
     * Set texteHaut
     *
     * @param string $texteHaut
     *
     * @return ModelePdf
     */
    public function setTexteHaut($texteHaut)
    {
        $this->texteHaut = $texteHaut;

        return $this;
    }

    /**
     * Get texteHaut
     *
     * @return string
     */
    public function getTexteHaut()
    {
        return $this->texteHaut;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return ModelePdf
     */
    public function setAgence(\AppBundle\Entity\Agence $agence = null)
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * Get agence
     *
     * @return \AppBundle\Entity\Agence
     */
    public function getAgence()
    {
        return $this->agence;
    }
}
