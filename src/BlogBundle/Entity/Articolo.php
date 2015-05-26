<?php
// src/Blogger/BlogBundle/Entity/Blog.php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Eko\FeedBundle\Item\Reader\ItemInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="articolo")
 * @ORM\HasLifecycleCallbacks()
 */
class Articolo implements ItemInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $titolo;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $permalink;

    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\User")
     * @JoinColumn(name="id_autore", referencedColumnName="id")
     **/
    protected $autore;

    /**
     * @ManyToOne(targetEntity="Categoria")
     * @JoinColumn(name="id_categoria", referencedColumnName="id")
     **/
    protected $categoria;

    /**
     * @ORM\Column(type="text")
     */
    protected $testo;

    /**
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $profilePictureFile;

    // for temporary storage
    private $tempimmagine;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $immagine;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $tags;


    /**
     * @var string
     *
     * @ORM\Column(name="stato", type="string", columnDefinition="ENUM('ATTIVO','BOZZA','DISATTIVO')", nullable=false)
     */
    private $stato;


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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $idComunicato;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitolo()
    {
        return $this->titolo;
    }

    /**
     * @param mixed $titolo
     */
    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
    }

    /**
     * @return mixed
     */
    public function getAutore()
    {
        return $this->autore;
    }

    /**
     * @param mixed $autore
     */
    public function setAutore($autore)
    {
        $this->autore = $autore;
    }

    /**
     * @return mixed
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * @param mixed $categoria
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;
    }

    /**
     * @return mixed
     */
    public function getTesto()
    {
        return $this->testo;
    }

    /**
     * @param mixed $testo
     */
    public function setTesto($testo)
    {
        $this->testo = $testo;
    }

    /**
     * @return mixed
     */
    public function getImmagine()
    {
        return $this->immagine;
    }

    /**
     * @param mixed $immagine
     */
    public function setImmagine($immagine)
    {
        $this->immagine = $immagine;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * This method sets feed item title
     *
     * @param string $title
     *
     */
    public function setFeedItemTitle($title)
    {
        // TODO: Implement setFeedItemTitle() method.
    }

    /**
     * This method sets feed item description (or content)
     *
     * @param string $description
     *
     */
    public function setFeedItemDescription($description)
    {
        // TODO: Implement setFeedItemDescription() method.
    }

    /**
     * This method sets feed item URL link
     *
     * @param string $link
     *
     */
    public function setFeedItemLink($link)
    {
        // TODO: Implement setFeedItemLink() method.
    }

    /**
     * This method sets item publication date
     *
     * @param \DateTime $date
     *
     */
    public function setFeedItemPubDate(\DateTime $date)
    {
        // TODO: Implement setFeedItemPubDate() method.
    }

    /**
     * @return mixed
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param mixed $permalink
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
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
    public function getIdComunicato()
    {
        return $this->idComunicato;
    }

    /**
     * @param mixed $idComunicato
     */
    public function setIdComunicato($idComunicato)
    {
        $this->idComunicato = $idComunicato;
    }

    /**
     * @ORM\PrePersist
     */
    public function generatePermalink()
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $this->titolo."-".rand(1, 99));
        $this->permalink = strtolower($slug);
    }

    /**
     * @return string
     */
    public function getStato()
    {
        return $this->stato;
    }

    /**
     * @param string $stato
     */
    public function setStato($stato)
    {
        $this->stato = $stato;
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
        if (isset($this->immagine)) {
            // store the old name to delete after the update
            $this->tempimmagine = $this->immagine;
            $this->immagine = null;
        } else {
            $this->immagine = 'initial';
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
     * Get the absolute path of the immagine
     */
    public function getProfilePictureAbsolutePath() {
        return null === $this->immagine
            ? null
            : $this->getUploadRootDir().'/'.$this->immagine;
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
        return 'images/articoli';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebimmagine() {

        return '/'.$this->getUploadDir().'/'.$this->getimmagine();
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
            $filename=str_replace(" ","-",$this->getTitolo())."-".$oggi->format("U");
            $this->setimmagine($filename.'.'.$this->getProfilePictureFile()->guessExtension());
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
        $this->getProfilePictureFile()->move($this->getUploadRootDir(), $this->getimmagine());

        // check if we have an old image
        if (isset($this->tempimmagine) && file_exists($this->getUploadRootDir().'/'.$this->tempimmagine)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempimmagine);
            // clear the temp image path
            $this->tempimmagine = null;
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


    public function __toString(){
        return $this->titolo;
    }



}
