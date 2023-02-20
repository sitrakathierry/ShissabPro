<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pannier
 *
 * @ORM\Table(name="pannier")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PannierRepository")
 */
class Pannier
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
     * @var float
     *
     * @ORM\Column(name="pu", type="float", precision=10, scale=0, nullable=true)
     */
    private $pu;

    /**
     * @var float
     *
     * @ORM\Column(name="qte", type="float", precision=10, scale=0, nullable=true)
     */
    private $qte;

    /**
     * @var float
     *
     * @ORM\Column(name="total", type="float", precision=10, scale=0, nullable=true)
     */
    private $total;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

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
     * @var \AppBundle\Entity\Commande
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Commande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="commande", referencedColumnName="id")
     * })
     */
    private $commande;

    /**
     * @var \AppBundle\Entity\BonCommande
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BonCommande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bon_commande", referencedColumnName="id")
     * })
     */
    private $bonCommande;


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
     * Set pu
     *
     * @param string $pu
     *
     * @return Pannier
     */
    public function setPu($pu)
    {
        $this->pu = $pu;
    
        return $this;
    }

    /**
     * Get pu
     *
     * @return string
     */
    public function getPu()
    {
        return $this->pu;
    }

    /**
     * Set qte
     *
     * @param string $qte
     *
     * @return Pannier
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
     * Set total
     *
     * @param string $total
     *
     * @return Pannier
     */
    public function setTotal($total)
    {
        $this->total = $total;
    
        return $this;
    }

    /**
     * Get total
     *
     * @return string
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Pannier
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Pannier
     */
    public function setCommande(\AppBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;
    
        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set variationProduit
     *
     * @param \AppBundle\Entity\VariationProduit $variationProduit
     *
     * @return Pannier
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
     * Set bonCommande
     *
     * @param \AppBundle\Entity\BonCommande $bonCommande
     *
     * @return Pannier
     */
    public function setBonCommande(\AppBundle\Entity\BonCommande $bonCommande = null)
    {
        $this->bonCommande = $bonCommande;

        return $this;
    }

    /**
     * Get bonCommande
     *
     * @return \AppBundle\Entity\BonCommande
     */
    public function getBonCommande()
    {
        return $this->bonCommande;
    }
}
