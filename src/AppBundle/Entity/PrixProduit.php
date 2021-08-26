<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PrixProduit
 *
 * @ORM\Table(name="prix_produit", indexes={@ORM\Index(name="fk_prix_produit_agence_id_idx", columns={"agence_id"}), @ORM\Index(name="fk_prix_produit_produit_id_idx", columns={"produit_id"})})
 * @ORM\Entity
 */
class PrixProduit
{
    /**
     * @var float
     *
     * @ORM\Column(name="prix_vente", type="float", precision=10, scale=0, nullable=true)
     */
    private $prixVente;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=true)
     */
    private $stock;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Produit
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Produit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     * })
     */
    private $produit;

    /**
     * @var \AppBundle\Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence_id", referencedColumnName="id")
     * })
     */
    private $agence;



    /**
     * Set prixVente
     *
     * @param float $prixVente
     *
     * @return PrixProduit
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return float
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }

    /**
     * Set stock
     *
     * @param integer $stock
     *
     * @return PrixProduit
     */
    public function setStock($stock)
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return integer
     */
    public function getStock()
    {
        return $this->stock;
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
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return PrixProduit
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
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return PrixProduit
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
