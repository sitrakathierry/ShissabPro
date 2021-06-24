<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientMorale
 *
 * @ORM\Table(name="client_morale", indexes={@ORM\Index(name="fk_client_morale_type_societe_idx", columns={"id_type_societe"})})
 * @ORM\Entity
 */
class ClientMorale
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom_societe", type="string", length=45, nullable=true)
     */
    private $nomSociete = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="nom_gerant", type="string", length=45, nullable=true)
     */
    private $nomGerant = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="tel_fixe", type="string", length=45, nullable=true)
     */
    private $telFixe = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=45, nullable=true)
     */
    private $fax = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="domaine", type="string", length=45, nullable=true)
     */
    private $domaine = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="num_registre", type="string", length=45, nullable=true)
     */
    private $numRegistre = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="nom_pers_contact", type="string", length=45, nullable=true)
     */
    private $nomPersContact = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="tel_pers_contact", type="string", length=45, nullable=true)
     */
    private $telPersContact = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="email_pers_contact", type="string", length=45, nullable=true)
     */
    private $emailPersContact = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\TypeSociete
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeSociete")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_societe", referencedColumnName="id")
     * })
     */
    private $idTypeSociete;



    /**
     * Set nomSociete
     *
     * @param string $nomSociete
     *
     * @return ClientMorale
     */
    public function setNomSociete($nomSociete)
    {
        $this->nomSociete = $nomSociete;

        return $this;
    }

    /**
     * Get nomSociete
     *
     * @return string
     */
    public function getNomSociete()
    {
        return $this->nomSociete;
    }

    /**
     * Set nomGerant
     *
     * @param string $nomGerant
     *
     * @return ClientMorale
     */
    public function setNomGerant($nomGerant)
    {
        $this->nomGerant = $nomGerant;

        return $this;
    }

    /**
     * Get nomGerant
     *
     * @return string
     */
    public function getNomGerant()
    {
        return $this->nomGerant;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return ClientMorale
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set telFixe
     *
     * @param string $telFixe
     *
     * @return ClientMorale
     */
    public function setTelFixe($telFixe)
    {
        $this->telFixe = $telFixe;

        return $this;
    }

    /**
     * Get telFixe
     *
     * @return string
     */
    public function getTelFixe()
    {
        return $this->telFixe;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return ClientMorale
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ClientMorale
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set domaine
     *
     * @param string $domaine
     *
     * @return ClientMorale
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;

        return $this;
    }

    /**
     * Get domaine
     *
     * @return string
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set numRegistre
     *
     * @param string $numRegistre
     *
     * @return ClientMorale
     */
    public function setNumRegistre($numRegistre)
    {
        $this->numRegistre = $numRegistre;

        return $this;
    }

    /**
     * Get numRegistre
     *
     * @return string
     */
    public function getNumRegistre()
    {
        return $this->numRegistre;
    }

    /**
     * Set nomPersContact
     *
     * @param string $nomPersContact
     *
     * @return ClientMorale
     */
    public function setNomPersContact($nomPersContact)
    {
        $this->nomPersContact = $nomPersContact;

        return $this;
    }

    /**
     * Get nomPersContact
     *
     * @return string
     */
    public function getNomPersContact()
    {
        return $this->nomPersContact;
    }

    /**
     * Set telPersContact
     *
     * @param string $telPersContact
     *
     * @return ClientMorale
     */
    public function setTelPersContact($telPersContact)
    {
        $this->telPersContact = $telPersContact;

        return $this;
    }

    /**
     * Get telPersContact
     *
     * @return string
     */
    public function getTelPersContact()
    {
        return $this->telPersContact;
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
     * Set idTypeSociete
     *
     * @param \AppBundle\Entity\TypeSociete $idTypeSociete
     *
     * @return ClientMorale
     */
    public function setIdTypeSociete(\AppBundle\Entity\TypeSociete $idTypeSociete = null)
    {
        $this->idTypeSociete = $idTypeSociete;

        return $this;
    }

    /**
     * Get idTypeSociete
     *
     * @return \AppBundle\Entity\TypeSociete
     */
    public function getIdTypeSociete()
    {
        return $this->idTypeSociete;
    }

    /**
     * Set emailPersContact
     *
     * @param string $emailPersContact
     *
     * @return ClientMorale
     */
    public function setEmailPersContact($emailPersContact)
    {
        $this->emailPersContact = $emailPersContact;

        return $this;
    }

    /**
     * Get emailPersContact
     *
     * @return string
     */
    public function getEmailPersContact()
    {
        return $this->emailPersContact;
    }
}
