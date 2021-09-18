<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureDetails
 *
 * @ORM\Table(name="facture_details", indexes={@ORM\Index(name="fk_facture_details_facture_idx", columns={"facture"})})
 * @ORM\Entity
 */
class FactureDetails
{
    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="text", nullable=true)
     */
    private $designation = '';

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
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Facture
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Facture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture", referencedColumnName="id")
     * })
     */
    private $facture;



    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return FactureDetails
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

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return FactureDetails
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
     * Set montant
     *
     * @param string $montant
     *
     * @return FactureDetails
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return FactureDetails
     */
    public function setFacture(\AppBundle\Entity\Facture $facture = null)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \AppBundle\Entity\Facture
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set qte
     *
     * @param string $qte
     *
     * @return FactureDetails
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
}
