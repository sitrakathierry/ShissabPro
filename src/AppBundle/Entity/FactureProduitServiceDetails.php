<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureProduitServiceDetails
 *
 * @ORM\Table(name="facture_produit_service_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureProduitServiceDetailsRepository")
 */
class FactureProduitServiceDetails
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
     * @var \AppBundle\Entity\VariationProduit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\VariationProduit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="variation_produit", referencedColumnName="id")
     * })
     */
    private $variationProduit;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="qte", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $qte = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant = '0.00';

    /**
     * @var \AppBundle\Entity\Service
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service", referencedColumnName="id")
     * })
     */
    private $service;

    /**
     * @var string
     *
     * @ORM\Column(name="periode", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $periode = '0.00';

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $duree = '0.00';

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @var \AppBundle\Entity\FactureProduitService
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FactureProduitService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture_produit_service", referencedColumnName="id")
     * })
     */
    private $factureProduitService;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_remise", type="integer", nullable=true)
     */
    private $typeRemise = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_remise", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantRemise = '0.00';


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
     * Set prix
     *
     * @param string $prix
     *
     * @return FactureProduitServiceDetails
     */
    public function setPrix($prix)
    {
        $this->prix = $prix ? $prix : '0.00';

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
     * @return FactureProduitServiceDetails
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
     * @return FactureProduitServiceDetails
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
     * Set periode
     *
     * @param string $periode
     *
     * @return FactureProduitServiceDetails
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode ? $periode : '0.00';

        return $this;
    }

    /**
     * Get periode
     *
     * @return string
     */
    public function getPeriode()
    {
        return $this->periode;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return FactureProduitServiceDetails
     */
    public function setDuree($duree)
    {
        $this->duree = $duree ? $duree : '0.00';

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return FactureProduitServiceDetails
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set variationProduit
     *
     * @param \AppBundle\Entity\VariationProduit $variationProduit
     *
     * @return FactureProduitServiceDetails
     */
    public function setVariationProduit(\AppBundle\Entity\VariationProduit $variationProduit = null)
    {
        $this->variationProduit = $variationProduit;

        return $this;
    }

    /**
     * Get variationProduit
     *
     * @return \AppBundle\Entity\VariationProduit
     */
    public function getVariationProduit()
    {
        return $this->variationProduit;
    }

    /**
     * Set service
     *
     * @param \AppBundle\Entity\Service $service
     *
     * @return FactureProduitServiceDetails
     */
    public function setService(\AppBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \AppBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set factureProduitService
     *
     * @param \AppBundle\Entity\FactureProduitService $factureProduitService
     *
     * @return FactureProduitServiceDetails
     */
    public function setFactureProduitService(\AppBundle\Entity\FactureProduitService $factureProduitService = null)
    {
        $this->factureProduitService = $factureProduitService;

        return $this;
    }

    /**
     * Get factureProduitService
     *
     * @return \AppBundle\Entity\FactureProduitService
     */
    public function getFactureProduitService()
    {
        return $this->factureProduitService;
    }

    /**
     * Set typeRemise
     *
     * @param integer $typeRemise
     *
     * @return FactureProduitServiceDetails
     */
    public function setTypeRemise($typeRemise)
    {
        $this->typeRemise = $typeRemise;

        return $this;
    }

    /**
     * Get typeRemise
     *
     * @return integer
     */
    public function getTypeRemise()
    {
        return $this->typeRemise;
    }

    /**
     * Set montantRemise
     *
     * @param string $montantRemise
     *
     * @return FactureProduitServiceDetails
     */
    public function setMontantRemise($montantRemise)
    {
        $this->montantRemise = $montantRemise ? $montantRemise : '0.00';

        return $this;
    }

    /**
     * Get montantRemise
     *
     * @return string
     */
    public function getMontantRemise()
    {
        return $this->montantRemise;
    }
}
