<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoTypeTache
 *
 * @ORM\Table(name="histo_type_tache")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistoTypeTacheRepository")
 */
class HistoTypeTache
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
     * @ORM\Column(name="idtache", type="integer")
     */
    private $idtache;

    /**
     * @var int
     *
     * @ORM\Column(name="idtypetache", type="integer")
     */
    private $idtypetache;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created_at", type="datetime")
     */
    private $dateCreatedAt;


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
     * Set idtache
     *
     * @param integer $idtache
     *
     * @return HistoTypeTache
     */
    public function setIdtache($idtache)
    {
        $this->idtache = $idtache;

        return $this;
    }

    /**
     * Get idtache
     *
     * @return int
     */
    public function getIdtache()
    {
        return $this->idtache;
    }

    /**
     * Set idtypetache
     *
     * @param integer $idtypetache
     *
     * @return HistoTypeTache
     */
    public function setIdtypetache($idtypetache)
    {
        $this->idtypetache = $idtypetache;

        return $this;
    }

    /**
     * Get idtypetache
     *
     * @return int
     */
    public function getIdtypetache()
    {
        return $this->idtypetache;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return HistoTypeTache
     */
    public function setDateCreatedAt($dateCreatedAt)
    {
        $this->dateCreatedAt = $dateCreatedAt;

        return $this;
    }

    /**
     * Get dateCreatedAt
     *
     * @return \DateTime
     */
    public function getDateCreatedAt()
    {
        return $this->dateCreatedAt;
    }
}

