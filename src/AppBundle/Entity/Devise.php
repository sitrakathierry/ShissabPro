<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Devise
 *
 * @ORM\Table(name="devise")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeviseRepository")
 */
class Devise
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
     * @ORM\Column(name="symbole", type="text", nullable=true)
     */
    private $symbole;

    /**
     * @var string
     *
     * @ORM\Column(name="lettre", type="text", nullable=true)
     */
    private $lettre;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_principal", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_conversion", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantConversion;

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
     * Set symbole
     *
     * @param string $symbole
     *
     * @return Devise
     */
    public function setSymbole($symbole)
    {
        $this->symbole = $symbole;

        return $this;
    }

    /**
     * Get symbole
     *
     * @return string
     */
    public function getSymbole()
    {
        return $this->symbole;
    }

    /**
     * Set lettre
     *
     * @param string $lettre
     *
     * @return Devise
     */
    public function setLettre($lettre)
    {
        $this->lettre = $lettre;

        return $this;
    }

    /**
     * Get lettre
     *
     * @return string
     */
    public function getLettre()
    {
        return $this->lettre;
    }

    /**
     * Set montantPrincipal
     *
     * @param string $montantPrincipal
     *
     * @return Devise
     */
    public function setMontantPrincipal($montantPrincipal)
    {
        $this->montantPrincipal = $montantPrincipal;

        return $this;
    }

    /**
     * Get montantPrincipal
     *
     * @return string
     */
    public function getMontantPrincipal()
    {
        return $this->montantPrincipal;
    }

    /**
     * Set montantConversion
     *
     * @param string $montantConversion
     *
     * @return Devise
     */
    public function setMontantConversion($montantConversion)
    {
        $this->montantConversion = $montantConversion;

        return $this;
    }

    /**
     * Get montantConversion
     *
     * @return string
     */
    public function getMontantConversion()
    {
        return $this->montantConversion;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return Devise
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
