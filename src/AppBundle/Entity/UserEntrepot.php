<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserEntrepot
 *
 * @ORM\Table(name="user_entrepot")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserEntrepotRepository")
 */
class UserEntrepot
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
     * @var \AppBundle\Entity\Entrepot
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Entrepot")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="entrepot", referencedColumnName="id")
     * })
     */
    private $entrepot;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set entrepot
     *
     * @param \AppBundle\Entity\Entrepot $entrepot
     *
     * @return UserEntrepot
     */
    public function setEntrepot(\AppBundle\Entity\Entrepot $entrepot = null)
    {
        $this->entrepot = $entrepot;

        return $this;
    }

    /**
     * Get entrepot
     *
     * @return \AppBundle\Entity\Entrepot
     */
    public function getEntrepot()
    {
        return $this->entrepot;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserEntrepot
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
