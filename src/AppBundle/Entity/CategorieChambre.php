<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategorieChambre
 *
 * @ORM\Table(name="categorie_chambre")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategorieChambreRepository")
 */
class CategorieChambre
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
     * @ORM\Column(name="nom", type="text", nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="caracteristiques", type="text", nullable=true)
     */
    private $caracteristiques;

    /**
     * @var \AppBundle\Entity\TypeChambre
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeChambre")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_chambre", referencedColumnName="id")
     * })
     */
    private $typeChambre;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return CategorieChambre
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CategorieChambre
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
     * Set caracteristiques
     *
     * @param string $caracteristiques
     *
     * @return CategorieChambre
     */
    public function setCaracteristiques($caracteristiques)
    {
        $this->caracteristiques = $caracteristiques;

        return $this;
    }

    /**
     * Get caracteristiques
     *
     * @return string
     */
    public function getCaracteristiques()
    {
        return $this->caracteristiques;
    }

    /**
     * Set typeChambre
     *
     * @param \AppBundle\Entity\TypeChambre $typeChambre
     *
     * @return CategorieChambre
     */
    public function setTypeChambre(\AppBundle\Entity\TypeChambre $typeChambre = null)
    {
        $this->typeChambre = $typeChambre;

        return $this;
    }

    /**
     * Get typeChambre
     *
     * @return \AppBundle\Entity\TypeChambre
     */
    public function getTypeChambre()
    {
        return $this->typeChambre;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return CategorieChambre
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
}
