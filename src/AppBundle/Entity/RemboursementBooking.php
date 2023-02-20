<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RemboursementBooking
 *
 * @ORM\Table(name="remboursement_booking")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RemboursementBookingRepository")
 */
class RemboursementBooking
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="pourcentage", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $pourcentage;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montant;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return RemboursementBooking
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set pourcentage
     *
     * @param string $pourcentage
     *
     * @return RemboursementBooking
     */
    public function setPourcentage($pourcentage)
    {
        $this->pourcentage = $pourcentage ? $pourcentage : '0.00';

        return $this;
    }

    /**
     * Get pourcentage
     *
     * @return string
     */
    public function getPourcentage()
    {
        return $this->pourcentage;
    }

    /**
     * Set montant
     *
     * @param string $montant
     *
     * @return RemboursementBooking
     */
    public function setMontant($montant)
    {
        $this->montant = $montant ? $montant : '0.00';

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }
}

