<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BonLivraison
 *
 * @ORM\Table(name="bon_livraison")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BonLivraisonRepository")
 */
class BonLivraison
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer", nullable=true)
     */
    private $statut;

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
     * @var \AppBundle\Entity\Client
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Client")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="client", referencedColumnName="num_police")
     * })
     */
    private $client;

    private $num;

    /**
     * @var \AppBundle\Entity\ModelePdf
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ModelePdf")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modele_pdf", referencedColumnName="id")
     * })
     */
    private $modelePdf;

    /**
     * @var \AppBundle\Entity\BonCommande
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\BonCommande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bon_commande", referencedColumnName="id")
     * })
     */
    private $bonCommande;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="text", nullable=true)
     */
    private $lieu;

    /**
     * @var int
     *
     * @ORM\Column(name="is_delete", type="integer", nullable=true)
     */
    private $is_delete;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BonLivraison
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
     * Set statut
     *
     * @param integer $statut
     *
     * @return BonLivraison
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return BonLivraison
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
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return BonLivraison
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set modelePdf
     *
     * @param \AppBundle\Entity\ModelePdf $modelePdf
     *
     * @return BonLivraison
     */
    public function setModelePdf(\AppBundle\Entity\ModelePdf $modelePdf = null)
    {
        $this->modelePdf = $modelePdf;

        return $this;
    }

    /**
     * Get modelePdf
     *
     * @return \AppBundle\Entity\ModelePdf
     */
    public function getModelePdf()
    {
        return $this->modelePdf;
    }

    /**
     * Set bonCommande
     *
     * @param \AppBundle\Entity\BonCommande $bonCommande
     *
     * @return BonLivraison
     */
    public function setBonCommande(\AppBundle\Entity\BonCommande $bonCommande = null)
    {
        $this->bonCommande = $bonCommande;

        return $this;
    }

    /**
     * Get bonCommande
     *
     * @return \AppBundle\Entity\BonCommande
     */
    public function getBonCommande()
    {
        return $this->bonCommande;
    }

    /**
     * Get num
     *
     */
    public function getNum()
    {

        $id = $this->id;

        $id = str_pad($id, 6, '0', STR_PAD_LEFT);

        $this->num = $id;

        return $this->num;

    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Facture
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set isDelete
     *
     * @param integer $isDelete
     *
     * @return Facture
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;

        return $this;
    }

    /**
     * Get isDelete
     *
     * @return integer
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}
