<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FactureRestaurantDetails
 *
 * @ORM\Table(name="facture_restaurant_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRestaurantDetailsRepository")
 */
class FactureRestaurantDetails
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
     * @var \AppBundle\Entity\FactureRestaurant
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FactureRestaurant")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="facture_restaurant", referencedColumnName="id")
     * })
     */
    private $factureRestaurant;

    /**
     * @var string
     *
     * @ORM\Column(name="accompagnements", type="text", nullable=true)
     */
    private $accompagnements;

    private $accompagnementsList;

    private $totalAccompagnement;


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
     * @return FactureRestaurantDetails
     */
    public function setQte($qte)
    {
        $this->qte = $qte ? $qte : '0.00';

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
     * @return FactureRestaurantDetails
     */
    public function setPrix($prix)
    {
        $this->prix = $prix ? $prix : '0.00';

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
     * @return FactureRestaurantDetails
     */
    public function setTotal($total)
    {
        $this->total = $total ? $total : '0.00';

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
     * @return FactureRestaurantDetails
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
     * @return FactureRestaurantDetails
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
     * Set factureRestaurant
     *
     * @param \AppBundle\Entity\FactureRestaurant $factureRestaurant
     *
     * @return FactureRestaurantDetails
     */
    public function setFactureRestaurant(\AppBundle\Entity\FactureRestaurant $factureRestaurant = null)
    {
        $this->factureRestaurant = $factureRestaurant;

        return $this;
    }

    /**
     * Get factureRestaurant
     *
     * @return \AppBundle\Entity\FactureRestaurant
     */
    public function getFactureRestaurant()
    {
        return $this->factureRestaurant;
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
}
