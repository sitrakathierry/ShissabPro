<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureProduit
 *
 * @ORM\Table(name="facture_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureProduitRepository")
 */
class FactureProduit
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
     * @var \AppBundle\Entity\Facture
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Facture")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture", referencedColumnName="id")
     * })
     */
    private $facture;

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
     * @var int
     *
     * @ORM\Column(name="is_delete", type="integer", nullable=true)
     */
    private $is_delete;

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
     * @return FactureProduit
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
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return FactureProduit
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
     * Set bonCommande
     *
     * @param \AppBundle\Entity\BonCommande $bonCommande
     *
     * @return FactureProduit
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

        /**
     * Set isDelete
     *
     * @param integer $isDelete
     *
     * @return Facture
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return integer
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }

}
