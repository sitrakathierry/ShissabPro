<?php

namespace Api\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="Api\ConfigBundle\Repository\ConfigRepository")
 */
class Config implements UserInterface
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
     * @ORM\Column(name="apiKey", type="string", length=50, unique=true)
     */
    private $apiKey;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return user
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    
    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        
    }

    public function getPassword()
    {
        
    }

    public function getRoles()
    {
        return ['ROLE_API'];
    }

    public function getUsername()
    {
        // code...
    }

    /**
     * Set siteweb
     *
     * @param \AppBundle\Entity\Siteweb $siteweb
     *
     * @return Config
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
}
