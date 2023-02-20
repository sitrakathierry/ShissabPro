<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Commentaire
 *
 * @ORM\Table(name="commentaire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentaireRepository")
 */
class Commentaire
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
     * @ORM\Column(name="idPcomment", type="integer")
     */
    private $idPcomment;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var int
     *
     * @ORM\Column(name="idtache", type="integer")
     */
    private $idtache;

    /**
     * @var string
     *
     * @ORM\Column(name="type_comment", type="string", length=255)
     */
    private $typeComment;

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
     * Set idPcomment
     *
     * @param integer $idPcomment
     *
     * @return Commentaire
     */
    public function setIdPcomment($idPcomment)
    {
        $this->idPcomment = $idPcomment;

        return $this;
    }

    /**
     * Get idPcomment
     *
     * @return int
     */
    public function getIdPcomment()
    {
        return $this->idPcomment;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Commentaire
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set idtache
     *
     * @param integer $idtache
     *
     * @return Commentaire
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
     * Set typeComment
     *
     * @param string $typeComment
     *
     * @return Commentaire
     */
    public function setTypeComment($typeComment)
    {
        $this->typeComment = $typeComment;

        return $this;
    }

    /**
     * Get typeComment
     *
     * @return string
     */
    public function getTypeComment()
    {
        return $this->typeComment;
    }

    /**
     * Set dateCreatedAt
     *
     * @param \DateTime $dateCreatedAt
     *
     * @return Commentaire
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

