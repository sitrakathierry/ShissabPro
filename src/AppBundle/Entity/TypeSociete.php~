<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSociete
 *
 * @ORM\Table(name="type_societe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeSocieteRepository")
 */
class TypeSociete
{
    /**
     * @var string
     *
     * @ORM\Column(name="designation", type="string", length=45, nullable=true)
     */
    private $designation = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=45, nullable=true)
     */
    private $desc = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;



    /**
     * Set designation
     *
     * @param string $designation
     *
     * @return TypeSociete
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;

        return $this;
    }

    /**
     * Get designation
     *
     * @return string
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * Set desc
     *
     * @param string $desc
     *
     * @return TypeSociete
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
