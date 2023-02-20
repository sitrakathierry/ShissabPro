<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Decharge
 *
 * @ORM\Table(name="decharge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DechargeRepository")
 */
class Decharge
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
     * @ORM\Column(name="beneficiaire", type="string", length=255, nullable=true)
     */
    private $beneficiaire;

    /**
     * @var string
     *
     * @ORM\Column(name="cheque", type="string", length=255, nullable=true)
     */
    private $cheque;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="tireur", type="string", length=255, nullable=true)
     */
    private $tireur;

    /**
     * @var string
     *
     * @ORM\Column(name="raison", type="text", nullable=true)
     */
    private $raison;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_validation", type="datetime", nullable=true)
     */
    private $dateValidation;

    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="lettre", type="text", nullable=true)
     */
    private $lettre;

    /**
     * @var integer
     *
     * @ORM\Column(name="mode_paiement", type="integer", nullable=true)
     */
    private $modePaiement = 1;

    /**
     * @var string
     *
     * @ORM\Column(name="devise", type="string", length=45, nullable=true)
     */

    private $devise;

    /**
     * @var integer
     *
     * @ORM\Column(name="motif", type="integer", nullable=true)
     */
    private $motif = 2;

    /**
     * @var string
     *
     * @ORM\Column(name="type_motif", type="string", length=255, nullable=true)
     */
    private $typeMotif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cheque", type="datetime", nullable=true)
     */
    private $dateCheque = null;

    /**
     * @var \AppBundle\Entity\MotifDecharge
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MotifDecharge")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="motif_decharge", referencedColumnName="id")
     * })
     */
    private $motifDecharge;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="mois_facture", type="date", nullable=true)
     */
    private $moisFacture;

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
     * @var \DateTime
     *
     * @ORM\Column(name="date_virement", type="datetime", nullable=true)
     */
    private $dateVirement = null;

    /**
     * @var string
     *
     * @ORM\Column(name="virement", type="string", length=255, nullable=true)
     */
    private $virement;

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Decharge
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return integer
     */
    public function getStatut()
    {
        return $this->statut;
    }


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
     * Set beneficiaire
     *
     * @param string $beneficiaire
     *
     * @return Decharge
     */
    public function setBeneficiaire($beneficiaire)
    {
        $this->beneficiaire = $beneficiaire;

        return $this;
    }

    /**
     * Get beneficiaire
     *
     * @return string
     */
    public function getBeneficiaire()
    {
        return $this->beneficiaire;
    }

    /**
     * Set cheque
     *
     * @param string $cheque
     *
     * @return Decharge
     */
    public function setCheque($cheque)
    {
        $this->cheque = $cheque;

        return $this;
    }

    /**
     * Get cheque
     *
     * @return string
     */
    public function getCheque()
    {
        return $this->cheque;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return Decharge
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
     * Set tireur
     *
     * @param string $tireur
     *
     * @return Decharge
     */
    public function setTireur($tireur)
    {
        $this->tireur = $tireur;

        return $this;
    }

    /**
     * Get tireur
     *
     * @return string
     */
    public function getTireur()
    {
        return $this->tireur;
    }

    /**
     * Set raison
     *
     * @param string $raison
     *
     * @return Decharge
     */
    public function setRaison($raison)
    {
        $this->raison = $raison;

        return $this;
    }

    /**
     * Get raison
     *
     * @return string
     */
    public function getRaison()
    {
        return $this->raison;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Decharge
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
     * Set lettre
     *
     * @param string $lettre
     *
     * @return Decharge
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
     * Set modePaiement
     *
     * @param integer $modePaiement
     *
     * @return Decharge
     */
    public function setModePaiement($modePaiement)
    {
        $this->modePaiement = $modePaiement;

        return $this;
    }

    /**
     * Get modePaiement
     *
     * @return integer
     */
    public function getModePaiement()
    {
        return $this->modePaiement;
    }

    /**
     * Set devise
     *
     * @param string $devise
     *
     * @return Decharge
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return string
     */
    public function getDevise()
    {
        return $this->devise;
    }

    /**
     * Set motif
     *
     * @param integer $motif
     *
     * @return Decharge
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return integer
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set typeMotif
     *
     * @param string $typeMotif
     *
     * @return Decharge
     */
    public function setTypeMotif($typeMotif)
    {
        $this->typeMotif = $typeMotif;

        return $this;
    }

    /**
     * Get typeMotif
     *
     * @return string
     */
    public function getTypeMotif()
    {
        return $this->typeMotif;
    }

    /**
     * Set dateCheque
     *
     * @param \DateTime $dateCheque
     *
     * @return Decharge
     */
    public function setDateCheque($dateCheque = null)
    {
        $this->dateCheque = $dateCheque;

        return $this;
    }

    /**
     * Get dateCheque
     *
     * @return \DateTime
     */
    public function getDateCheque()
    {
        return $this->dateCheque;
    }

    /**
     * Set motifDecharge
     *
     * @param \AppBundle\Entity\MotifDecharge $motifDecharge
     *
     * @return Decharge
     */
    public function setMotifDecharge(\AppBundle\Entity\MotifDecharge $motifDecharge = null)
    {
        $this->motifDecharge = $motifDecharge;

        return $this;
    }

    /**
     * Get motifDecharge
     *
     * @return \AppBundle\Entity\MotifDecharge
     */
    public function getMotifDecharge()
    {
        return $this->motifDecharge;
    }

    /**
     * Set moisFacture
     *
     * @param \DateTime $moisFacture
     *
     * @return Decharge
     */
    public function setMoisFacture($moisFacture = null)
    {
        $this->moisFacture = $moisFacture;

        return $this;
    }

    /**
     * Get moisFacture
     *
     * @return \DateTime
     */
    public function getMoisFacture()
    {
        return $this->moisFacture;
    }

    /**
     * Set dateValidation
     *
     * @param \DateTime $dateValidation
     *
     * @return Decharge
     */
    public function setDateValidation($dateValidation = null)
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    /**
     * Get dateValidation
     *
     * @return \DateTime
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return Decharge
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

    /**
     * Set dateVirement
     *
     * @param \DateTime $dateVirement
     *
     * @return Decharge
     */
    public function setDateVirement($dateVirement = null)
    {
        $this->dateVirement = $dateVirement;

        return $this;
    }

    /**
     * Get dateVirement
     *
     * @return \DateTime
     */
    public function getDateVirement()
    {
        return $this->dateVirement;
    }

    /**
     * Set virement
     *
     * @param string $virement
     *
     * @return Decharge
     */
    public function setVirement($virement)
    {
        $this->virement = $virement;

        return $this;
    }

    /**
     * Get virement
     *
     * @return string
     */
    public function getVirement()
    {
        return $this->virement;
    }
}
