<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetailsDepense
 *
 * @ORM\Table(name="details_depense")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DetailsDepenseRepository")
 */
class DetailsDepense
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
     * @var int
     *
     * @ORM\Column(name="IdDecharge", type="integer", nullable=true)
     */
    private $idDecharge;

    /**
     * @var int
     *
     * @ORM\Column(name="id_designation", type="integer", nullable=true)
     */
    private $idDesingation;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer", nullable=true)
     */
    private $quantite;

    /**
     * @var int
     *
     * @ORM\Column(name="prix_unitaire", type="integer", nullable=true)
     */
    private $prixUnitaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set idDecharge
     *
     * @param integer $idDecharge
     *
     * @return DetailsDepense
     */
    public function setIdDecharge($idDecharge)
    {
        $this->idDecharge = $idDecharge;

        return $this;
    }

    /**
     * Get idDecharge
     *
     * @return int
     */
    public function getIdDecharge()
    {
        return $this->idDecharge;
    }

    /**
     * Set idDesingation
     *
     * @param integer $idDesingation
     *
     * @return DetailsDepense
     */
    public function setIdDesignation($idDesingation)
    {
        $this->idDesingation = $idDesingation;

        return $this;
    }

    /**
     * Get idDesingation
     *
     * @return int
     */
    public function getIdDesignation()
    {
        return $this->idDesingation;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return DetailsDepense
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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return DetailsDepense
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixUnitaire
     *
     * @param integer $prixUnitaire
     *
     * @return DetailsDepense
     */
    public function setPrixUnitaire($prixUnitaire)
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * Get prixUnitaire
     *
     * @return int
     */
    public function getPrixUnitaire()
    {
        return $this->prixUnitaire;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return DetailsDepense
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return DetailsDepense
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

