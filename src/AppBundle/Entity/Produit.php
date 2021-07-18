<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\Column(name="code", type="text", unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="text")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_achat", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prixAchat;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Produit
     */
    public function setCode($code)
    {
        $this->code = $code;
    
        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Produit
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
     * Set description
     *
     * @param string $description
     *
     * @return Produit
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
     * Set prixAchat
     *
     * @param string $prixAchat
     *
     * @return Produit
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;
    
        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return string
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set prixVente
     *
     * @param string $prixVente
     *
     * @return Produit
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
     * @return Produit
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
}

