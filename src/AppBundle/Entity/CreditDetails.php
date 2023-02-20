<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CreditDetails
 *
 * @ORM\Table(name="credit_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CreditDetailsRepository")
 */
class CreditDetails
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
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="qte", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $qte;

    /**
     * @var string
     *
     * @ORM\Column(name="periode", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $periode;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $duree;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant;

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
     * @var \AppBundle\Entity\Service
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Service")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service", referencedColumnName="id")
     * })
     */
    private $service;

    /**
     * @var \AppBundle\Entity\Credit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Credit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="credit", referencedColumnName="id")
     * })
     */
    private $credit;

    /**
     * @var int
     *
     * @ORM\Column(name="type_remise", type="integer", nullable=true)
     */
    private $typeRemise;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_remise", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantRemise;


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
     * Set type
     *
     * @param integer $type
     *
     * @return CreditDetails
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
     * Set qte
     *
     * @param string $qte
     *
     * @return CreditDetails
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
     * Set periode
     *
     * @param string $periode
     *
     * @return CreditDetails
     */
    public function setPeriode($periode)
    {
        $this->periode = $periode;

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
     * @return CreditDetails
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

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
     * Set prix
     *
     * @param string $prix
     *
     * @return CreditDetails
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
     * @return CreditDetails
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
     * Set typeRemise
     *
     * @param integer $typeRemise
     *
     * @return CreditDetails
     */
    public function setTypeRemise($typeRemise)
    {
        $this->typeRemise = $typeRemise;

        return $this;
    }

    /**
     * Get typeRemise
     *
     * @return int
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
     * @return CreditDetails
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

    /**
     * Set variationProduit
     *
     * @param \AppBundle\Entity\VariationProduit $variationProduit
     *
     * @return CreditDetails
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
     * @return CreditDetails
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
     * Set credit
     *
     * @param \AppBundle\Entity\Credit $credit
     *
     * @return CreditDetails
     */
    public function setCredit(\AppBundle\Entity\Credit $credit = null)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return \AppBundle\Entity\Credit
     */
    public function getCredit()
    {
        return $this->credit;
    }
}
