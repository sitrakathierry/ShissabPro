<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mouvement
 *
 * @ORM\Table(name="mouvement", indexes={@ORM\Index(name="fk_mouvement_compte_bancaire_idx", columns={"compte_bancaire"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MouvementRepository")
 */
class Mouvement
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="operation", type="integer", nullable=true)
     */
    private $operation = '';

    /**
     * @var string
     *
     * @ORM\Column(name="num_operation", type="string", length=45, nullable=true)
     */
    private $numOperation = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="type_operation", type="integer", nullable=true)
     */
    private $typeOperation = '';

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant = '';

    /**
     * @var string
     *
     * @ORM\Column(name="op_nom", type="string", length=45, nullable=true)
     */
    private $opNom = '';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\CompteBancaire
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CompteBancaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="compte_bancaire", referencedColumnName="id")
     * })
     */
    private $compteBancaire;



    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Mouvement
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
     * Set operation
     *
     * @param integer $operation
     *
     * @return Mouvement
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return integer
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set numOperation
     *
     * @param string $numOperation
     *
     * @return Mouvement
     */
    public function setNumOperation($numOperation)
    {
        $this->numOperation = $numOperation;

        return $this;
    }

    /**
     * Get numOperation
     *
     * @return string
     */
    public function getNumOperation()
    {
        return $this->numOperation;
    }

    /**
     * Set typeOperation
     *
     * @param integer $typeOperation
     *
     * @return Mouvement
     */
    public function setTypeOperation($typeOperation)
    {
        $this->typeOperation = $typeOperation;

        return $this;
    }

    /**
     * Get typeOperation
     *
     * @return integer
     */
    public function getTypeOperation()
    {
        return $this->typeOperation;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return Mouvement
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
     * Set opNom
     *
     * @param string $opNom
     *
     * @return Mouvement
     */
    public function setOpNom($opNom)
    {
        $this->opNom = $opNom;

        return $this;
    }

    /**
     * Get opNom
     *
     * @return string
     */
    public function getOpNom()
    {
        return $this->opNom;
    }

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
     * Set compteBancaire
     *
     * @param \AppBundle\Entity\CompteBancaire $compteBancaire
     *
     * @return Mouvement
     */
    public function setCompteBancaire(\AppBundle\Entity\CompteBancaire $compteBancaire = null)
    {
        $this->compteBancaire = $compteBancaire;

        return $this;
    }

    /**
     * Get compteBancaire
     *
     * @return \AppBundle\Entity\CompteBancaire
     */
    public function getCompteBancaire()
    {
        return $this->compteBancaire;
    }
}
