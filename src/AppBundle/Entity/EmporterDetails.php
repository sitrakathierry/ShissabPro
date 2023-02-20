<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmporterDetails
 *
 * @ORM\Table(name="emporter_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmporterDetailsRepository")
 */
class EmporterDetails
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
     * @ORM\Column(name="qte", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $qte;

    /**
     * @var string
     *
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix;

    /**
     * @var string
     *
     * @ORM\Column(name="total", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $total;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut;

    /**
     * @var \AppBundle\Entity\Plat
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Plat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="plat", referencedColumnName="id")
     * })
     */
    private $plat;

    /**
     * @var \AppBundle\Entity\Emporter
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Emporter")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="emporter", referencedColumnName="id")
     * })
     */
    private $emporter;

    /**
     * @var string
     *
     * @ORM\Column(name="accompagnements", type="text", nullable=true)
     */
    private $accompagnements;

    private $accompagnementsList;

    private $totalAccompagnement;

    /**
     * @var string
     *
     * @ORM\Column(name="cuisson", type="text", nullable=true)
     */
    private $cuisson;


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
     * Set qte
     *
     * @param string $qte
     *
     * @return EmporterDetails
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
     * Set prix
     *
     * @param string $prix
     *
     * @return EmporterDetails
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
     * Set total
     *
     * @param string $total
     *
     * @return EmporterDetails
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
     * Set statut
     *
     * @param integer $statut
     *
     * @return EmporterDetails
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set plat
     *
     * @param \AppBundle\Entity\Plat $plat
     *
     * @return EmporterDetails
     */
    public function setPlat(\AppBundle\Entity\Plat $plat = null)
    {
        $this->plat = $plat;

        return $this;
    }

    /**
     * Get plat
     *
     * @return \AppBundle\Entity\Plat
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * Set emporter
     *
     * @param \AppBundle\Entity\Emporter $emporter
     *
     * @return EmporterDetails
     */
    public function setEmporter(\AppBundle\Entity\Emporter $emporter = null)
    {
        $this->emporter = $emporter;

        return $this;
    }

    /**
     * Get emporter
     *
     * @return \AppBundle\Entity\Emporter
     */
    public function getEmporter()
    {
        return $this->emporter;
    }

    /**
     * Set accompagnements
     *
     * @param string $accompagnements
     *
     * @return EmporterDetails
     */
    public function setAccompagnements($accompagnements)
    {
        $this->accompagnements = $accompagnements;

        return $this;
    }

    /**
     * Get accompagnements
     *
     * @return string
     */
    public function getAccompagnements()
    {
        return $this->accompagnements;
    }

    public function getAccompagnementsList($value='')
    {
        return $this->accompagnementsList;
    }

    public function setAccompagnementsList($accompagnementsList)
    {
        $this->accompagnementsList = $accompagnementsList;

        return $this;
    }

    public function getTotalAccompagnement($value='')
    {
        return $this->totalAccompagnement;
    }

    public function setTotalAccompagnement($totalAccompagnement)
    {
        $this->totalAccompagnement = $totalAccompagnement;

        return $this;
    }



    /**
     * Set cuisson
     *
     * @param string $cuisson
     *
     * @return EmporterDetails
     */
    public function setCuisson($cuisson)
    {
        $this->cuisson = $cuisson;

        return $this;
    }

    /**
     * Get cuisson
     *
     * @return string
     */
    public function getCuisson()
    {
        return $this->cuisson;
    }
}
