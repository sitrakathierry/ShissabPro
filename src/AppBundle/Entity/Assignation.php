<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Assignation
 *
 * @ORM\Table(name="assignation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AssignationRepository")
 */
class Assignation
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
     * @ORM\Column(name="idPersonne", type="integer")
     */
    private $idPersonne;

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
     * @return Assignation
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
     * Set idPersonne
     *
     * @param integer $idPersonne
     *
     * @return Assignation
     */
    public function setIdPersonne($idPersonne)
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

    /**
     * Get idPersonne
     *
     * @return int
     */
    public function getIdPersonne()
    {
        return $this->idPersonne;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return Assignation
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

