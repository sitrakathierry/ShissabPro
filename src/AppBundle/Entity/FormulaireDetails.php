<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormulaireDetails
 *
 * @ORM\Table(name="formulaire_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormulaireDetailsRepository")
 */
class FormulaireDetails
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
     * @ORM\Column(name="slug", type="text", nullable=true)
     */
    private $slug;

    /**
     * @var \AppBundle\Entity\Formulaire
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Formulaire")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="formulaire", referencedColumnName="id")
     * })
     */
    private $formulaire;


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
     * @return FormulaireDetails
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
     * Set slug
     *
     * @param string $slug
     *
     * @return FormulaireDetails
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set formulaire
     *
     * @param \AppBundle\Entity\Formulaire $formulaire
     *
     * @return FormulaireDetails
     */
    public function setFormulaire(\AppBundle\Entity\Formulaire $formulaire = null)
    {
        $this->formulaire = $formulaire;

        return $this;
    }

    /**
     * Get formulaire
     *
     * @return \AppBundle\Entity\Formulaire
     */
    public function getFormulaire()
    {
        return $this->formulaire;
    }
}
