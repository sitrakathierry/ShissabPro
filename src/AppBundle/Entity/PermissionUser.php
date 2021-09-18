<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PermissionUser
 *
 * @ORM\Table(name="permission_user", indexes={@ORM\Index(name="fk_permission_user_user_idx", columns={"user"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PermissionUserRepository")
 */
class PermissionUser
{
    /**
     * @var string
     *
     * @ORM\Column(name="permission", type="text", nullable=true)
     */
    private $permission = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id")
     * })
     */
    private $user;



    /**
     * Set permission
     *
     * @param string $permission
     *
     * @return PermissionUser
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission
     *
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
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

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return PermissionUser
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\FosUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
