<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

use Ibrows\Bundle\NewsletterBundle\Model\User\MandantUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser implements MandantUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="facebook_id", type="string", nullable=true)
     */
    private $facebookID;


    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=true)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="cognome", type="string", nullable=true)
     */
    private $cognome;


    /**
     * @var string
     *
     * @ORM\Column(name="firma", type="text", nullable=true)
     */
    private $firma;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", nullable=true)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="privacy", type="text", nullable=true)
     */
    private $privacy;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mandant;


    /**
     * @ORM\ManyToOne(targetEntity="Porto", inversedBy="commenti")
     * @ORM\JoinColumn(name="id_porto", referencedColumnName="id", nullable=true)
     **/
    private $porto;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Type("DateTime")
     */
    protected $timestamp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_update_timestamp", type="datetime", nullable=false)
     * @Gedmo\Timestampable(on="update")
     * @Serializer\Type("DateTime")
     */
    protected $lastUpdateTimestamp;



    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_USER');
        $this->porti = new ArrayCollection();
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
     * Set githubID
     *
     * @param string $githubID
     * @return User
     */
    public function setFacebookID($githubID)
    {
        $this->facebookID = $githubID;

        return $this;
    }

    /**
     * Get githubID
     *
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookID;
    }

    /**
     * @return string
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * @param string $cognome
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param string $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
    }


    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }


    /**
     * @return string
     */
    public function getFirma()
    {
        return $this->firma;
    }

    /**
     * @param string $firma
     */
    public function setFirma($firma)
    {
        $this->firma = $firma;
    }

    /**
     * @return string
     */
    public function getPrivacy()
    {
        return $this->privacy;
    }

    /**
     * @param string $privacy
     */
    public function setPrivacy($privacy)
    {
        $this->privacy = $privacy;
    }

    /**
     * @return string
     */
    public function getMandant()
    {
        return $this->mandant;
    }

    public function isGranted($role)
    {
        return in_array($role, $this->getRoles());
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param \DateTime $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return \DateTime
     */
    public function getLastUpdateTimestamp()
    {
        return $this->lastUpdateTimestamp;
    }

    /**
     * @param \DateTime $lastUpdateTimestamp
     */
    public function setLastUpdateTimestamp($lastUpdateTimestamp)
    {
        $this->lastUpdateTimestamp = $lastUpdateTimestamp;
    }

    /**
     * @return mixed
     */
    public function getPorto()
    {
        return $this->porto;
    }

    /**
     * @param mixed $porto
     */
    public function setPorto($porto)
    {
        $this->porto = $porto;
    }


}
