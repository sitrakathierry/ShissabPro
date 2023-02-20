<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TarifCategorieChambre
 *
 * @ORM\Table(name="tarif_categorie_chambre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TarifCategorieChambreRepository")
 */
class TarifCategorieChambre
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
    private $petitDejeuner = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_petit_dejeuner", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantPetitDejeuner = '0.00';

    /**
     * @var \AppBundle\Entity\CategorieChambre
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\CategorieChambre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_chambre", referencedColumnName="id")
     * })
     */
    private $categorieChambre;


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
     * @return TarifCategorieChambre
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
     * @return TarifCategorieChambre
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
     * @return TarifCategorieChambre
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
     * @return TarifCategorieChambre
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
     * Set categorieChambre
     *
     * @param \AppBundle\Entity\CategorieChambre $categorieChambre
     *
     * @return TarifCategorieChambre
     */
    public function setCategorieChambre(\AppBundle\Entity\CategorieChambre $categorieChambre = null)
    {
        $this->categorieChambre = $categorieChambre;

        return $this;
    }

    /**
     * Get categorieChambre
     *
     * @return \AppBundle\Entity\CategorieChambre
     */
    public function getCategorieChambre()
    {
        return $this->categorieChambre;
    }
}
