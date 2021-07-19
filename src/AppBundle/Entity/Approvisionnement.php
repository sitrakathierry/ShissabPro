<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Approvisionnement
 *
 * @ORM\Table(name="approvisionnement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ApprovisionnementRepository")
 */
class Approvisionnement
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="qte", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $qte;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_achat", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prixAchat;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

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
     * @var \AppBundle\Entity\Ravitaillement
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ravitaillement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ravitaillement", referencedColumnName="id")
     * })
     */
    private $ravitaillement;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Approvisionnement
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
     * Set qte
     *
     * @param string $qte
     *
     * @return Approvisionnement
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
     * Set prixAchat
     *
     * @param string $prixAchat
     *
     * @return Approvisionnement
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
     * Set total
     *
     * @param string $total
     *
     * @return Approvisionnement
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
     * Set description
     *
     * @param string $description
     *
     * @return Approvisionnement
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
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return Approvisionnement
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
     * Set ravitaillement
     *
     * @param \AppBundle\Entity\Ravitaillement $ravitaillement
     *
     * @return Approvisionnement
     */
    public function setRavitaillement(\AppBundle\Entity\Ravitaillement $ravitaillement = null)
    {
        $this->ravitaillement = $ravitaillement;
    
        return $this;
    }

    /**
     * Get ravitaillement
     *
     * @return \AppBundle\Entity\Ravitaillement
     */
    public function getRavitaillement()
    {
        return $this->ravitaillement;
    }
}
