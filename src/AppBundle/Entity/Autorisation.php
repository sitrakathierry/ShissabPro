<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Autorisation
 *
 * @ORM\Table(name="autorisation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AutorisationRepository")
 */
class Autorisation
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
     * @var \AppBundle\Entity\Siteweb
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Siteweb")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="siteweb", referencedColumnName="id")
     * })
     */
    private $siteweb; 

    /**
     * @var \AppBundle\Entity\Module
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Module")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="module", referencedColumnName="id")
     * })
     */
    private $module; 


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
     * Set siteweb
     *
     * @param \AppBundle\Entity\Siteweb $siteweb
     *
     * @return Autorisation
     */
    public function setSiteweb(\AppBundle\Entity\Siteweb $siteweb = null)
    {
        $this->siteweb = $siteweb;

        return $this;
    }

    /**
     * Get siteweb
     *
     * @return \AppBundle\Entity\Siteweb
     */
    public function getSiteweb()
    {
        return $this->siteweb;
    }

    /**
     * Set module
     *
     * @param \AppBundle\Entity\Module $module
     *
     * @return Autorisation
     */
    public function setModule(\AppBundle\Entity\Module $module = null)
    {
        $this->module = $module;

        return $this;
    }

    /**
     * Get module
     *
     * @return \AppBundle\Entity\Module
     */
    public function getModule()
    {
        return $this->module;
    }
}
