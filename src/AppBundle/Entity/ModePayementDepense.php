<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModePayementDepense
 *
 * @ORM\Table(name="mode_payement_depense")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModePayementDepenseRepository")
 */
class ModePayementDepense
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
     * @ORM\Column(name="numero", type="string", length=500)
     */
    private $numero;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="idmodep", type="integer")
     */
    private $idmodep;

    /**
     * @var int
     *
     * @ORM\Column(name="iddepense", type="integer")
     */
    private $iddepense;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set numero
     *
     * @param string $numero
     *
     * @return ModePayementDepense
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ModePayementDepense
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
     * Set idmodep
     *
     * @param integer $idmodep
     *
     * @return ModePayementDepense
     */
    public function setIdmodep($idmodep)
    {
        $this->idmodep = $idmodep;

        return $this;
    }

    /**
     * Get idmodep
     *
     * @return int
     */
    public function getIdmodep()
    {
        return $this->idmodep;
    }

    /**
     * Set iddepense
     *
     * @param integer $iddepense
     *
     * @return ModePayementDepense
     */
    public function setIddepense($iddepense)
    {
        $this->iddepense = $iddepense;

        return $this;
    }

    /**
     * Get iddepense
     *
     * @return int
     */
    public function getIddepense()
    {
        return $this->iddepense;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ModePayementDepense
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return ModePayementDepense
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}

