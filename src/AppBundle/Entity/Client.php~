<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Client
 *
 * @ORM\Table(name="client", indexes={@ORM\Index(name="fk_client_morale_idx", columns={"id_client_morale"}), @ORM\Index(name="fk_client_physique_idx", columns={"id_client_physique"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client
{
    /**
     * @var integer
     *
     * @ORM\Column(name="statut", type="integer", nullable=false)
     */
    private $statut;

    /**
     * @var integer
     *
     * @ORM\Column(name="num_police", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $numPolice;

    /**
     * @var \AppBundle\Entity\ClientMorale
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ClientMorale")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client_morale", referencedColumnName="id")
     * })
     */
    private $idClientMorale;

    /**
     * @var \AppBundle\Entity\ClientPhysique
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ClientPhysique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_client_physique", referencedColumnName="id")
     * })
     */
    private $idClientPhysique;

    /**
     * @var \AppBundle\Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence", referencedColumnName="id")
     * })
     */
    private $agence;


    private $formattedNum;



    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return Client
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
     * Get numPolice
     *
     * @return integer
     */
    public function getNumPolice()
    {
        return $this->numPolice;
    }

    /**
     * Set idClientMorale
     *
     * @param \AppBundle\Entity\ClientMorale $idClientMorale
     *
     * @return Client
     */
    public function setIdClientMorale(\AppBundle\Entity\ClientMorale $idClientMorale = null)
    {
        $this->idClientMorale = $idClientMorale;

        return $this;
    }

    /**
     * Get idClientMorale
     *
     * @return \AppBundle\Entity\ClientMorale
     */
    public function getIdClientMorale()
    {
        return $this->idClientMorale;
    }

    /**
     * Set idClientPhysique
     *
     * @param \AppBundle\Entity\ClientPhysique $idClientPhysique
     *
     * @return Client
     */
    public function setIdClientPhysique(\AppBundle\Entity\ClientPhysique $idClientPhysique = null)
    {
        $this->idClientPhysique = $idClientPhysique;

        return $this;
    }

    /**
     * Get idClientPhysique
     *
     * @return \AppBundle\Entity\ClientPhysique
     */
    public function getIdClientPhysique()
    {
        return $this->idClientPhysique;
    }

    /**
     * Get formattedNum
     *
     * @return \AppBundle\Entity\ClientMorale
     */
    public function getFormattedNum()
    {

        $id = $this->numPolice;

        $id = str_pad($id, 6, '0', STR_PAD_LEFT);

        $this->formattedNum = $id;

        return $this->formattedNum;

        // $str = strval($id);
        // $len = strlen($str);
        // $zero = '';
        // for($i = 0; $i < (6 -$len); $i++){
        //     $zero .= '0';
        // }
        // $formatted = $zero . $str;
        // $this->formattedNum = $formatted;
        // return $this->formattedNum;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return Client
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
