<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slider
 *
 * @ORM\Table(name="slider")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SliderRepository")
 */
class Slider
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
     * @ORM\Column(name="titre", type="text", nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="sous_titre", type="text", nullable=true)
     */
    private $sousTitre;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="text", nullable=true)
     */
    private $img;

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
     * @var integer
     *
     * @ORM\Column(name="desactive", type="integer", nullable=true)
     */
    private $desactive;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return Slider
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set sousTitre
     *
     * @param string $sousTitre
     *
     * @return Slider
     */
    public function setSousTitre($sousTitre)
    {
        $this->sousTitre = $sousTitre;

        return $this;
    }

    /**
     * Get sousTitre
     *
     * @return string
     */
    public function getSousTitre()
    {
        return $this->sousTitre;
    }

    /**
     * Set img
     *
     * @param string $img
     *
     * @return Slider
     */
    public function setImg($img)
    {
        $this->img = $img;

        return $this;
    }

    /**
     * Get img
     *
     * @return string
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * Set siteweb
     *
     * @param \AppBundle\Entity\Siteweb $siteweb
     *
     * @return Slider
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
     * Set desactive
     *
     * @param integer $desactive
     *
     * @return Slider
     */
    public function setDesactive($desactive)
    {
        $this->desactive = $desactive;

        return $this;
    }

    /**
     * Get desactive
     *
     * @return integer
     */
    public function getDesactive()
    {
        return $this->desactive;
    }
}
