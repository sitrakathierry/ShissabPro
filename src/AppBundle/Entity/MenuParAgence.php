<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MenuParAgence
 *
 * @ORM\Table(name="menu_par_agence", indexes={@ORM\Index(name="fk_menu_par_agence_agence_id_idx", columns={"agence_id"}), @ORM\Index(name="fk_menu_par_agence_menu_id_idx", columns={"menu_id"})})
 * @ORM\Entity
 */
class MenuParAgence
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\Menu
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Menu")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="menu_id", referencedColumnName="id")
     * })
     */
    private $menu;

    /**
     * @var \AppBundle\Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence_id", referencedColumnName="id")
     * })
     */
    private $agence;



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
     * Set menu
     *
     * @param \AppBundle\Entity\Menu $menu
     *
     * @return MenuParAgence
     */
    public function setMenu(\AppBundle\Entity\Menu $menu = null)
    {
        $this->menu = $menu;

        return $this;
    }

    /**
     * Get menu
     *
     * @return \AppBundle\Entity\Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return MenuParAgence
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
