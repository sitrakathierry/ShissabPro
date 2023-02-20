<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StockInterneGeneral
 *
 * @ORM\Table(name="stock_interne_general")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StockInterneGeneralRepository")
 */
class StockInterneGeneral
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
     * @ORM\Column(name="nom", type="text", nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="stock", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $stock;

    /**
     * @var string
     *
     * @ORM\Column(name="unite", type="string", length=255, nullable=true)
     */
    private $unite;

    /**
     * @var string
     *
     * @ORM\Column(name="portion", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $portion;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \AppBundle\Entity\LibelleGeneral
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\LibelleGeneral")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="libelle_general", referencedColumnName="id")
     * })
     */
    private $libelleGeneral;

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
     * @var string
     *
     * @ORM\Column(name="fournisseurs", type="text", nullable=true)
     */
    private $fournisseurs;


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
     * @return StockInterneGeneral
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
     * Set prix
     *
     * @param string $prix
     *
     * @return StockInterneGeneral
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set stock
     *
     * @param string $stock
     *
     * @return StockInterneGeneral
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return string
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * Set unite
     *
     * @param string $unite
     *
     * @return StockInterneGeneral
     */
    public function setUnite($unite)
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * Get unite
     *
     * @return string
     */
    public function getUnite()
    {
        return $this->unite;
    }

    /**
     * Set portion
     *
     * @param string $portion
     *
     * @return StockInterneGeneral
     */
    public function setPortion($portion)
    {
        $this->portion = $portion;

        return $this;
    }

    /**
     * Get portion
     *
     * @return string
     */
    public function getPortion()
    {
        return $this->portion;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return StockInterneGeneral
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return StockInterneGeneral
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

    /**
     * Set libelleGeneral
     *
     * @param \AppBundle\Entity\LibelleGeneral $libelleGeneral
     *
     * @return StockInterneGeneral
     */
    public function setLibelleGeneral(\AppBundle\Entity\LibelleGeneral $libelleGeneral = null)
    {
        $this->libelleGeneral = $libelleGeneral;

        return $this;
    }

    /**
     * Get libelleGeneral
     *
     * @return \AppBundle\Entity\LibelleGeneral
     */
    public function getLibelleGeneral()
    {
        return $this->libelleGeneral;
    }

    /**
     * Set fournisseurs
     *
     * @param string $fournisseurs
     *
     * @return Approvisionnement
     */
    public function setFournisseurs($fournisseurs)
    {
        $this->fournisseurs = $fournisseurs;

        return $this;
    }

    /**
     * Get fournisseurs
     *
     * @return string
     */
    public function getFournisseurs()
    {
        return $this->fournisseurs;
    }
}
