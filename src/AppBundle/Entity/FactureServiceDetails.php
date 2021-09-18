<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureServiceDetails
 *
 * @ORM\Table(name="facture_service_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureServiceDetailsRepository")
 */
class FactureServiceDetails
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
    private $periode = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private $duree = '';

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix = '';

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant = '';

    /**
     * @var \AppBundle\Entity\FactureService
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FactureService")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture_service", referencedColumnName="id")
     * })
     */
    private $factureService;


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
     * Set periode
     *
     * @param string $periode
     *
     * @return FactureServiceDetails
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
     * @param integer $duree
     *
     * @return FactureServiceDetails
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    
        return $this;
    }

    /**
     * Get duree
     *
     * @return integer
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
     * @return FactureServiceDetails
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
     * @return FactureServiceDetails
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
     * Set service
     *
     * @param \AppBundle\Entity\Service $service
     *
     * @return FactureServiceDetails
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
     * Set factureService
     *
     * @param \AppBundle\Entity\FactureService $factureService
     *
     * @return FactureServiceDetails
     */
    public function setFactureService(\AppBundle\Entity\FactureService $factureService = null)
    {
        $this->factureService = $factureService;
    
        return $this;
    }

    /**
     * Get factureService
     *
     * @return \AppBundle\Entity\FactureService
     */
    public function getFactureService()
    {
        return $this->factureService;
    }
}
