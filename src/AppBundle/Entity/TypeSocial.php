<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeSocial
 *
 * @ORM\Table(name="type_social")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeSocialRepository")
 */
class TypeSocial
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
     * @return TypeSocial
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
     * @return TypeSocial
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
