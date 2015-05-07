<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @ORM\Column(name="firma", type="string", nullable=true)
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
     * @ORM\Column(name="privacy", type="string", nullable=true)
     */
    private $privacy;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $mandant;




    public function __construct()
    {
        parent::__construct();
        $this->roles = array('ROLE_USER');
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
}
