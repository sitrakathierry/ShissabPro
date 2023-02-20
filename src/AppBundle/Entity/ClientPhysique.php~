<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientPhysique
 *
 * @ORM\Table(name="client_physique", indexes={@ORM\Index(name="fk_client_physique_type_social_idx", columns={"id_type_social"})})
 * @ORM\Entity
 */
class ClientPhysique
{
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=45, nullable=true)
     */
    private $nom = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="nin", type="string", length=45, nullable=true)
     */
    private $nin = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=45, nullable=true)
     */
    private $adresse = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="quartier", type="string", length=45, nullable=true)
     */
    private $quartier = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=45, nullable=true)
     */
    private $tel = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="sexe", type="integer", nullable=true)
     */
    private $sexe = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=45, nullable=true)
     */
    private $email = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="situation", type="integer", nullable=true)
     */
    private $situation = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_travail", type="string", length=45, nullable=true)
     */
    private $lieuTravail = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="tel_pers_contact", type="string", length=45, nullable=true)
     */
    private $telPersContact = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="adresse_pers_contact", type="string", length=45, nullable=true)
     */
    private $adressePersContact = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="email_pers_contact", type="string", length=45, nullable=true)
     */
    private $emailPersContact = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="lien_parente", type="string", length=45, nullable=true)
     */
    private $lienParente = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="string", length=45, nullable=true)
     */
    private $observation = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="nom_pers_contact", type="string", length=45, nullable=true)
     */
    private $nomPersContact = 'NULL';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ddn", type="date", nullable=true)
     */
    private $ddn = null;

    /**
     * @var string
     *
     * @ORM\Column(name="ldn", type="string", length=45, nullable=true)
     */
    private $ldn = 'NULL';

    /**
     * @var string
     *
     * @ORM\Column(name="profession", type="string", length=45, nullable=true)
     */
    private $profession = 'NULL';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\TypeSocial
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeSocial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_type_social", referencedColumnName="id")
     * })
     */
    private $idTypeSocial;



    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return ClientPhysique
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set nin
     *
     * @param string $nin
     *
     * @return ClientPhysique
     */
    public function setNin($nin)
    {
        $this->nin = $nin;

        return $this;
    }

    /**
     * Get nin
     *
     * @return string
     */
    public function getNin()
    {
        return $this->nin;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return ClientPhysique
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
     * Set quartier
     *
     * @param string $quartier
     *
     * @return ClientPhysique
     */
    public function setQuartier($quartier)
    {
        $this->quartier = $quartier;

        return $this;
    }

    /**
     * Get quartier
     *
     * @return string
     */
    public function getQuartier()
    {
        return $this->quartier;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return ClientPhysique
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set sexe
     *
     * @param integer $sexe
     *
     * @return ClientPhysique
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return integer
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ClientPhysique
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
     * Set situation
     *
     * @param integer $situation
     *
     * @return ClientPhysique
     */
    public function setSituation($situation)
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * Get situation
     *
     * @return integer
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * Set lieuTravail
     *
     * @param string $lieuTravail
     *
     * @return ClientPhysique
     */
    public function setLieuTravail($lieuTravail)
    {
        $this->lieuTravail = $lieuTravail;

        return $this;
    }

    /**
     * Get lieuTravail
     *
     * @return string
     */
    public function getLieuTravail()
    {
        return $this->lieuTravail;
    }

    /**
     * Set telPersContact
     *
     * @param string $telPersContact
     *
     * @return ClientPhysique
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
     * Set adressePersContact
     *
     * @param string $adressePersContact
     *
     * @return ClientPhysique
     */
    public function setAdressePersContact($adressePersContact)
    {
        $this->adressePersContact = $adressePersContact;

        return $this;
    }

    /**
     * Get adressePersContact
     *
     * @return string
     */
    public function getAdressePersContact()
    {
        return $this->adressePersContact;
    }

    /**
     * Set emailPersContact
     *
     * @param string $emailPersContact
     *
     * @return ClientPhysique
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

    /**
     * Set lienParente
     *
     * @param string $lienParente
     *
     * @return ClientPhysique
     */
    public function setLienParente($lienParente)
    {
        $this->lienParente = $lienParente;

        return $this;
    }

    /**
     * Get lienParente
     *
     * @return string
     */
    public function getLienParente()
    {
        return $this->lienParente;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return ClientPhysique
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Set nomPersContact
     *
     * @param string $nomPersContact
     *
     * @return ClientPhysique
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
     * Set ddn
     *
     * @param \DateTime $ddn
     *
     * @return ClientPhysique
     */
    public function setDdn($ddn = null)
    {
        $this->ddn = $ddn;

        return $this;
    }

    /**
     * Get ddn
     *
     * @return \DateTime
     */
    public function getDdn()
    {
        return $this->ddn;
    }

    /**
     * Set ldn
     *
     * @param string $ldn
     *
     * @return ClientPhysique
     */
    public function setLdn($ldn)
    {
        $this->ldn = $ldn;

        return $this;
    }

    /**
     * Get ldn
     *
     * @return string
     */
    public function getLdn()
    {
        return $this->ldn;
    }

    /**
     * Set profession
     *
     * @param string $profession
     *
     * @return ClientPhysique
     */
    public function setProfession($profession)
    {
        $this->profession = $profession;

        return $this;
    }

    /**
     * Get profession
     *
     * @return string
     */
    public function getProfession()
    {
        return $this->profession;
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
     * Set idTypeSocial
     *
     * @param \AppBundle\Entity\TypeSocial $idTypeSocial
     *
     * @return ClientPhysique
     */
    public function setIdTypeSocial(\AppBundle\Entity\TypeSocial $idTypeSocial = null)
    {
        $this->idTypeSocial = $idTypeSocial;

        return $this;
    }

    /**
     * Get idTypeSocial
     *
     * @return \AppBundle\Entity\TypeSocial
     */
    public function getIdTypeSocial()
    {
        return $this->idTypeSocial;
    }
}
