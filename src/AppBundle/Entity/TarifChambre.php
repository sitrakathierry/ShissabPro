<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarifChambre
 *
 * @ORM\Table(name="tarif_chambre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TarifChambreRepository")
 */
class TarifChambre
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
     * @ORM\Column(name="nb_pers", type="integer", nullable=true)
     */
    private $nbPers = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant = '0.00';

    /**
     * @var int
     *
     * @ORM\Column(name="petit_dejeuner", type="integer", nullable=true)
     */
    private $petitDejeuner = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_petit_dejeuner", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantPetitDejeuner = '0.00';

    /**
     * @var \AppBundle\Entity\Chambre
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Chambre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="chambre", referencedColumnName="id")
     * })
     */
    private $chambre;


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
     * Set nbPers
     *
     * @param integer $nbPers
     *
     * @return TarifChambre
     */
    public function setNbPers($nbPers)
    {
        $this->nbPers = $nbPers;

        return $this;
    }

    /**
     * Get nbPers
     *
     * @return int
     */
    public function getNbPers()
    {
        return $this->nbPers;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return TarifChambre
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
     * Set petitDejeuner
     *
     * @param integer $petitDejeuner
     *
     * @return TarifChambre
     */
    public function setPetitDejeuner($petitDejeuner)
    {
        $this->petitDejeuner = $petitDejeuner;

        return $this;
    }

    /**
     * Get petitDejeuner
     *
     * @return int
     */
    public function getPetitDejeuner()
    {
        return $this->petitDejeuner;
    }

    /**
     * Set montantPetitDejeuner
     *
     * @param string $montantPetitDejeuner
     *
     * @return TarifChambre
     */
    public function setMontantPetitDejeuner($montantPetitDejeuner)
    {
        $this->montantPetitDejeuner = $montantPetitDejeuner ? $montantPetitDejeuner : '0.00';

        return $this;
    }

    /**
     * Get montantPetitDejeuner
     *
     * @return string
     */
    public function getMontantPetitDejeuner()
    {
        return $this->montantPetitDejeuner;
    }

    /**
     * Set chambre
     *
     * @param \AppBundle\Entity\Chambre $chambre
     *
     * @return TarifChambre
     */
    public function setChambre(\AppBundle\Entity\Chambre $chambre = null)
    {
        $this->chambre = $chambre;

        return $this;
    }

    /**
     * Get chambre
     *
     * @return \AppBundle\Entity\Chambre
     */
    public function getChambre()
    {
        return $this->chambre;
    }
}
