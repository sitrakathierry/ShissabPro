<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntreeSortieStockInterneGeneralDetails
 *
 * @ORM\Table(name="entree_sortie_stock_interne_general_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EntreeSortieStockInterneGeneralDetailsRepository")
 */
class EntreeSortieStockInterneGeneralDetails
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
     * @ORM\Column(name="portion", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $portion;

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
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \AppBundle\Entity\StockInterneGeneral
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\StockInterneGeneral")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stock_interne_general", referencedColumnName="id")
     * })
     */
    private $stockInterneGeneral;

    /**
     * @var \AppBundle\Entity\EntreeSortieStockInterneGeneral
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EntreeSortieStockInterneGeneral")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entree_sortie_stock_interne_general", referencedColumnName="id")
     * })
     */
    private $entreeSortieStockInterneGeneral;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="integer", nullable=true)
     */
    private $type;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return EntreeSortieStockInterneGeneralDetails
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
     * @return EntreeSortieStockInterneGeneralDetails
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
     * @return EntreeSortieStockInterneGeneralDetails
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
     * @return EntreeSortieStockInterneGeneralDetails
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
     * @return EntreeSortieStockInterneGeneralDetails
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
     * Set stockInterneGeneral
     *
     * @param \AppBundle\Entity\StockInterneGeneral $stockInterneGeneral
     *
     * @return EntreeSortieStockInterneGeneralDetails
     */
    public function setStockInterneGeneral(\AppBundle\Entity\StockInterneGeneral $stockInterneGeneral = null)
    {
        $this->stockInterneGeneral = $stockInterneGeneral;

        return $this;
    }

    /**
     * Get stockInterneGeneral
     *
     * @return \AppBundle\Entity\StockInterneGeneral
     */
    public function getStockInterneGeneral()
    {
        return $this->stockInterneGeneral;
    }

    /**
     * Set entreeSortieStockInterneGeneral
     *
     * @param \AppBundle\Entity\EntreeSortieStockInterneGeneral $entreeSortieStockInterneGeneral
     *
     * @return EntreeSortieStockInterneGeneralDetails
     */
    public function setEntreeSortieStockInterneGeneral(\AppBundle\Entity\EntreeSortieStockInterneGeneral $entreeSortieStockInterneGeneral = null)
    {
        $this->entreeSortieStockInterneGeneral = $entreeSortieStockInterneGeneral;

        return $this;
    }

    /**
     * Get entreeSortieStockInterneGeneral
     *
     * @return \AppBundle\Entity\EntreeSortieStockInterneGeneral
     */
    public function getEntreeSortieStockInterneGeneral()
    {
        return $this->entreeSortieStockInterneGeneral;
    }

    /**
     * Set portion
     *
     * @param string $portion
     *
     * @return EntreeSortieStockInterneGeneralDetails
     */
    public function setPortion($portion)
    {
        $this->portion = $portion;

        return $this;
    }

    /**
     * Get portion
     *
     * @return string
     */
    public function getPortion()
    {
        return $this->portion;
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return EntreeSortieStockInterneGeneralDetails
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }
}
