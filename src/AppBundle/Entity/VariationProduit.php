<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VariationProduit
 *
 * @ORM\Table(name="variation_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VariationProduitRepository")
 */
class VariationProduit
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
     * @ORM\Column(name="prix_vente", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prixVente;

    /**
     * @var string
     *
     * @ORM\Column(name="stock", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $stock;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set prixVente
     *
     * @param string $prixVente
     *
     * @return VariationProduit
     */
    public function setPrixVente($prixVente)
    {
        $this->prixVente = $prixVente;

        return $this;
    }

    /**
     * Get prixVente
     *
     * @return string
     */
    public function getPrixVente()
    {
        return $this->prixVente;
    }

    /**
     * Set stock
     *
     * @param string $stock
     *
     * @return VariationProduit
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
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return VariationProduit
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
}
