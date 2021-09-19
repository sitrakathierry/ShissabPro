<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureProduitDetails
 *
 * @ORM\Table(name="facture_produit_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureProduitDetailsRepository")
 */
class FactureProduitDetails
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
     * @var \AppBundle\Entity\Produit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produit", referencedColumnName="id")
     * })
     */
    private $produit;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix = '';

    /**
     * @var string
     *
     * @ORM\Column(name="qte", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $qte = '';

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant = '';

    /**
     * @var \AppBundle\Entity\FactureProduit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FactureProduit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture_produit", referencedColumnName="id")
     * })
     */
    private $factureProduit;

    /**
     * @var integer
     *
     * @ORM\Column(name="libre", type="integer", nullable=true)
     */
    private $libre;

    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="text", nullable=true)
     */
    private $designation;


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
     * Set prix
     *
     * @param string $prix
     *
     * @return FactureProduitDetails
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
     * Set qte
     *
     * @param string $qte
     *
     * @return FactureProduitDetails
     */
    public function setQte($qte)
    {
        $this->qte = $qte;
    
        return $this;
    }

    /**
     * Get qte
     *
     * @return string
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return FactureProduitDetails
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    
        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return FactureProduitDetails
     */
    public function setProduit(\AppBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;
    
        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }


    /**
     * Set factureProduit
     *
     * @param \AppBundle\Entity\FactureProduit $factureProduit
     *
     * @return FactureProduitDetails
     */
    public function setFactureProduit(\AppBundle\Entity\FactureProduit $factureProduit = null)
    {
        $this->factureProduit = $factureProduit;
    
        return $this;
    }

    /**
     * Get factureProduit
     *
     * @return \AppBundle\Entity\FactureProduit
     */
    public function getFactureProduit()
    {
        return $this->factureProduit;
    }

    /**
     * Set libre
     *
     * @param integer $libre
     *
     * @return FactureProduitDetails
     */
    public function setLibre($libre)
    {
        $this->libre = $libre;

        return $this;
    }

    /**
     * Get libre
     *
     * @return integer
     */
    public function getLibre()
    {
        return $this->libre;
    }

    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return FactureProduitDetails
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }
}
