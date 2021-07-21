<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PdfAgence
 *
 * @ORM\Table(name="pdf_agence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PdfAgenceRepository")
 */
class PdfAgence
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
     * @var \AppBundle\Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence", referencedColumnName="id")
     * })
     */
    private $agence;

    /**
     * @var \AppBundle\Entity\ModelePdf
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ModelePdf")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture", referencedColumnName="id")
     * })
     */
    private $facture;

    /**
     * @var \AppBundle\Entity\ModelePdf
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ModelePdf")
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
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return PdfAgence
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

    /**
     * Set facture
     *
     * @param \AppBundle\Entity\ModelePdf $facture
     *
     * @return PdfAgence
     */
    public function setFacture(\AppBundle\Entity\ModelePdf $facture = null)
    {
        $this->facture = $facture;

        return $this;
    }

    /**
     * Get facture
     *
     * @return \AppBundle\Entity\ModelePdf
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\ModelePdf $produit
     *
     * @return PdfAgence
     */
    public function setProduit(\AppBundle\Entity\ModelePdf $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\ModelePdf
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
