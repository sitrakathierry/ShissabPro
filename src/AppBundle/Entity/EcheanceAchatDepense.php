<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EcheanceAchatDepense
 *
 * @ORM\Table(name="echeance_achat_depense")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EcheanceAchatDepenseRepository")
 */
class EcheanceAchatDepense
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
     * @ORM\Column(name="idDepense", type="integer")
     */
    private $idDepense;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEch", type="date")
     */
    private $dateEch;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer")
     */
    private $montant;

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
     * Set idDepense
     *
     * @param integer $idDepense
     *
     * @return EcheanceAchatDepense
     */
    public function setIdDepense($idDepense)
    {
        $this->idDepense = $idDepense;

        return $this;
    }

    /**
     * Get idDepense
     *
     * @return int
     */
    public function getIdDepense()
    {
        return $this->idDepense;
    }

    /**
     * Set dateEch
     *
     * @param \DateTime $dateEch
     *
     * @return EcheanceAchatDepense
     */
    public function setDateEch($dateEch)
    {
        $this->dateEch = $dateEch;

        return $this;
    }

    /**
     * Get dateEch
     *
     * @return \DateTime
     */
    public function getDateEch()
    {
        return $this->dateEch;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return EcheanceAchatDepense
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EcheanceAchatDepense
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
     * @return EcheanceAchatDepense
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

