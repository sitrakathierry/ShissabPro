<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BonLivraisonDetails
 *
 * @ORM\Table(name="bon_livraison_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BonLivraisonDetailsRepository")
 */
class BonLivraisonDetails
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
    private $type = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="qte", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $qte = '0.00';

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
     * @var \AppBundle\Entity\BonLivraison
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BonLivraison")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bon_livraison", referencedColumnName="id")
     * })
     */
    private $bonLivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description = '';

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
     * @return BonLivraisonDetails
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
     * @return BonLivraisonDetails
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
     * @return BonLivraisonDetails
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
     * @return BonLivraisonDetails
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
     * Set variationProduit
     *
     * @param \AppBundle\Entity\VariationProduit $variationProduit
     *
     * @return BonLivraisonDetails
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
     * @return BonLivraisonDetails
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
     * Set bonLivraison
     *
     * @param \AppBundle\Entity\BonLivraison $bonLivraison
     *
     * @return BonLivraisonDetails
     */
    public function setBonLivraison(\AppBundle\Entity\BonLivraison $bonLivraison = null)
    {
        $this->bonLivraison = $bonLivraison;

        return $this;
    }

    /**
     * Get bonLivraison
     *
     * @return \AppBundle\Entity\BonLivraison
     */
    public function getBonLivraison()
    {
        return $this->bonLivraison;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return BonLivraisonDetails
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
}
