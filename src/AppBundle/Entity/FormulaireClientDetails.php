<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormulaireClientDetails
 *
 * @ORM\Table(name="formulaire_client_details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FormulaireClientDetailsRepository")
 */
class FormulaireClientDetails
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
     * @var \AppBundle\Entity\FormulaireDetails
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FormulaireDetails")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="formulaire_details", referencedColumnName="id")
     * })
     */
    private $formulaireDetails;

        /**
     * @var \AppBundle\Entity\FormulaireClient
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\FormulaireClient")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="formulaire_client", referencedColumnName="id")
     * })
     */
    private $formulaireClient;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="text", nullable=true)
     */
    private $valeur;


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
     * Set formulaireDetails
     *
     * @param \AppBundle\Entity\FormulaireDetails $formulaireDetails
     *
     * @return FormulaireClientDetails
     */
    public function setFormulaireDetails(\AppBundle\Entity\FormulaireDetails $formulaireDetails = null)
    {
        $this->formulaireDetails = $formulaireDetails;

        return $this;
    }

    /**
     * Get formulaireDetails
     *
     * @return \AppBundle\Entity\FormulaireDetails
     */
    public function getFormulaireDetails()
    {
        return $this->formulaireDetails;
    }

    /**
     * Set formulaireClient
     *
     * @param \AppBundle\Entity\FormulaireClient $formulaireClient
     *
     * @return FormulaireClientDetails
     */
    public function setFormulaireClient(\AppBundle\Entity\FormulaireClient $formulaireClient = null)
    {
        $this->formulaireClient = $formulaireClient;

        return $this;
    }

    /**
     * Get formulaireClient
     *
     * @return \AppBundle\Entity\FormulaireClient
     */
    public function getFormulaireClient()
    {
        return $this->formulaireClient;
    }

    /**
     * Set valeur
     *
     * @param string $valeur
     *
     * @return FormulaireClientDetails
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }
}
