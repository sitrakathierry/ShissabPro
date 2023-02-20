<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PdfAgence
 *
 * @ORM\Table(name="pdf_agence")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PdfAgenceRepository")
 */
class PdfAgence
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
     * @var \AppBundle\Entity\Agence
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Agence")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="agence", referencedColumnName="id")
     * })
     */
    private $agence;

    /**
     * @var int
     *
     * @ORM\Column(name="objet", type="integer")
     */
    private $objet;

    /**
     * @var \AppBundle\Entity\ModelePdf
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ModelePdf")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="modele_pdf", referencedColumnName="id")
     * })
     */
    private $modelePdf;

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
     * Set agence
     *
     * @param \AppBundle\Entity\Agence $agence
     *
     * @return PdfAgence
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


    /**
     * Set objet
     *
     * @param integer $objet
     *
     * @return PdfAgence
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return integer
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set modelePdf
     *
     * @param \AppBundle\Entity\ModelePdf $modelePdf
     *
     * @return PdfAgence
     */
    public function setModelePdf(\AppBundle\Entity\ModelePdf $modelePdf = null)
    {
        $this->modelePdf = $modelePdf;

        return $this;
    }

    /**
     * Get modelePdf
     *
     * @return \AppBundle\Entity\ModelePdf
     */
    public function getModelePdf()
    {
        return $this->modelePdf;
    }
}
