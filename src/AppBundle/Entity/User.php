<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;


use Ibrows\Bundle\NewsletterBundle\Model\User\MandantUserInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks()
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
     * @Assert\File(maxSize="2048k")
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $profilePictureFile;

    // for temporary storage
    private $tempProfilePicturePath;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $profilePicturePath;

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
     * @ORM\Column(name="id_originale",type="integer", nullable=true)
     */
    protected $idOriginale;



    /**
     * @ORM\OneToMany(targetEntity="Attracco", mappedBy="utente")
     **/
    private $attracchi;


    /**
     * @ORM\OneToMany(targetEntity="AnnuncioScambioPosto", mappedBy="utente")
     **/
    private $annuncioScambioPosto;


    /**
     * @ORM\OneToMany(targetEntity="AnnuncioImbarco", mappedBy="utente")
     **/
    private $annuncioImbarco;


    /**
     * @ORM\OneToMany(targetEntity="CCDNForum\ForumBundle\Entity\Post", mappedBy="createdBy")
     **/
    private $messaggi;


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
     * @return mixed
     */
    public function getMessaggi()
    {
        return $this->messaggi;
    }

    /**
     * @param mixed $messaggi
     */
    public function setMessaggi($messaggi)
    {
        $this->messaggi = $messaggi;
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
     * Sets the file used for profile picture uploads
     *
     * @param UploadedFile $file
     * @return object
     */
    public function setProfilePictureFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file = null) {
        // set the value of the holder
        $this->profilePictureFile       =   $file;
        // check if we have an old image path
        if (isset($this->profilePicturePath)) {
            // store the old name to delete after the update
            $this->tempProfilePicturePath = $this->profilePicturePath;
            $this->profilePicturePath = null;
        } else {
            $this->profilePicturePath = 'initial';
        }

        return $this;
    }

    /**
     * Get the file used for profile picture uploads
     *
     * @return UploadedFile
     */
    public function getProfilePictureFile() {

        return $this->profilePictureFile;
    }

    /**
     * Set profilePicturePath
     *
     * @param string $profilePicturePath
     * @return User
     */
    public function setProfilePicturePath($profilePicturePath)
    {
        $this->profilePicturePath = $profilePicturePath;

        return $this;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getProfilePicturePath()
    {
        return $this->profilePicturePath;
    }

    /**
     * Get the absolute path of the profilePicturePath
     */
    public function getProfilePictureAbsolutePath() {
        return null === $this->profilePicturePath
            ? null
            : $this->getUploadRootDir().'/'.$this->profilePicturePath;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir($type='profilePicture') {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir($type);
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir($type='profilePicture') {
        // the type param is to change these methods at a later date for more file uploads
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/utenti/profilo';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath() {

        return '/'.$this->getUploadDir().'/'.$this->getProfilePicturePath();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadProfilePicture() {
        if (null !== $this->getProfilePictureFile()) {
            // a file was uploaded
            // generate a unique filename
            $oggi=new \DateTime();
            $filename=$this->getUsername()."-".$oggi->format("U");
            $this->setProfilePicturePath($filename.'.'.$this->getProfilePictureFile()->guessExtension());
        }
    }

    /**
     * Generates a 32 char long random filename
     *
     * @return string
     */
    public function generateRandomProfilePictureFilename() {
        $count                  =   0;
        do {
            $oggi=new \DateTime();
            $randomString = bin2hex($oggi->format("U"));
            $count++;
        }
        while(file_exists($this->getUploadRootDir().'/'.$randomString.'.'.$this->getProfilePictureFile()->guessExtension()) && $count < 50);

        return $randomString;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadProfilePicture() {
        // check there is a profile pic to upload
        if ($this->getProfilePictureFile() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getProfilePictureFile()->move($this->getUploadRootDir(), $this->getProfilePicturePath());

        // check if we have an old image
        if (isset($this->tempProfilePicturePath) && file_exists($this->getUploadRootDir().'/'.$this->tempProfilePicturePath)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempProfilePicturePath);
            // clear the temp image path
            $this->tempProfilePicturePath = null;
        }
        $this->profilePictureFile = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeProfilePictureFile()
    {
        if ($file = $this->getProfilePictureAbsolutePath() && file_exists($this->getProfilePictureAbsolutePath())) {
            unlink($file);
        }
    }

    /**
     * @return mixed
     */
    public function getAttracchi()
    {
        return $this->attracchi;
    }

    /**
     * @param mixed $attracchi
     */
    public function setAttracchi($attracchi)
    {
        $this->attracchi = $attracchi;
    }

    /**
     * @return mixed
     */
    public function getIdOriginale()
    {
        return $this->idOriginale;
    }

    /**
     * @param mixed $idOriginale
     */
    public function setIdOriginale($idOriginale)
    {
        $this->idOriginale = $idOriginale;
    }

    /**
     * @return mixed
     */
    public function getAnnuncioImbarco()
    {
        return $this->annuncioImbarco;
    }

    /**
     * @param mixed $annuncioImbarco
     */
    public function setAnnuncioImbarco($annuncioImbarco)
    {
        $this->annuncioImbarco = $annuncioImbarco;
    }

    /**
     * @return mixed
     */
    public function getAnnuncioScambioPosto()
    {
        return $this->annuncioScambioPosto;
    }

    /**
     * @param mixed $annuncioScambioPosto
     */
    public function setAnnuncioScambioPosto($annuncioScambioPosto)
    {
        $this->annuncioScambioPosto = $annuncioScambioPosto;
    }



}
