<?php
// src/Blogger/BlogBundle/Entity/Blog.php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\OneToMany;
use Eko\FeedBundle\Item\Writer\ItemInterface;
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
     * @ManyToOne(targetEntity="AppBundle\Entity\GalleriaFoto")
     * @JoinColumn(name="id_galleria", referencedColumnName="id", nullable=true)
     **/
    protected $gallery;

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
     * @ORM\Column(type="text", nullable=true)
     */
    protected $sottotitolo;


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
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $idOriginale;

    /**
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $immagineCorrelata;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $immagineCorrelataArticolo;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $titoloCorrelato;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $testoCorrelato;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $linkCorrelato;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $allegato1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $allegato2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $allegato3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $allegato4;


    /**
     * @Assert\File()
     */
    protected $allegato1File;
    /**
     * @Assert\File()
     */
    protected $allegato2File;
    /**
     * @Assert\File()
     */
    protected $allegato3File;
    /**
     * @Assert\File()
     */
    protected $allegato4File;


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
    public function setProfilePictureFile(\Symfony\Component\HttpFoundation\File\UploadedFile $file = null)
    {
        // set the value of the holder

        $this->profilePictureFile = $file;
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
    public function getProfilePictureFile()
    {

        return $this->profilePictureFile;
    }


    /**
     * Get the absolute path of the immagine
     */
    public function getProfilePictureAbsolutePath()
    {
        return null === $this->immagine
            ? null
            : $this->getUploadRootDir().'/'.$this->immagine;
    }

    /**
     * Get root directory for file uploads
     *
     * @return string
     */
    protected function getUploadRootDir($type = 'profilePicture')
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir($type);
    }

    /**
     * Specifies where in the /web directory profile pic uploads are stored
     *
     * @return string
     */
    protected function getUploadDir($type = 'profilePicture')
    {
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
    public function getWebimmagine()
    {

        return '/'.$this->getUploadDir().'/'.$this->getimmagine();
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadProfilePicture()
    {
        if (null !== $this->getProfilePictureFile()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getTitolo())."-".$oggi->format("U"))
            );
            $this->setimmagine($filename.'.'.$this->getProfilePictureFile()->guessExtension());
        }
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadImmagineCorrelata()
    {
        if (null !== $this->getImmagineCorrelata()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getTitoloCorrelato())."-".$oggi->format("U"))
            );
            $this->setImmagineCorrelataArticolo($filename.'.'.$this->getImmagineCorrelata()->guessExtension());
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadAllegato1()
    {
        if (null !== $this->getAllegato1File()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getAllegato1File()->getClientOriginalName())."-".$oggi->format("U"))
            );
            $this->setAllegato1($filename.'.'.$this->getAllegato1File()->guessExtension());
        }
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadAllegato2()
    {
        if (null !== $this->getAllegato2File()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getAllegato2File()->getClientOriginalName())."-".$oggi->format("U"))
            );
            $this->setAllegato2($filename.'.'.$this->getAllegato2File()->guessExtension());
        }
    }


    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadAllegato3()
    {
        if (null !== $this->getAllegato3File()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getAllegato3File()->getClientOriginalName())."-".$oggi->format("U"))
            );
            $this->setAllegato3($filename.'.'.$this->getAllegato3File()->guessExtension());
        }
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadAllegato4()
    {
        if (null !== $this->getAllegato4File()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getAllegato4File()->getClientOriginalName())."-".$oggi->format("U"))
            );
            $this->setAllegato4($filename.'.'.$this->getAllegato4File()->guessExtension());
        }
    }

    /**
     * Generates a 32 char long random filename
     *
     * @return string
     */
    public function generateRandomProfilePictureFilename()
    {
        $count = 0;
        do {
            $oggi = new \DateTime();
            $randomString = bin2hex($oggi->format("U"));
            $count++;
        } while (file_exists(
                $this->getUploadRootDir().'/'.$randomString.'.'.$this->getProfilePictureFile()->guessExtension()
            ) && $count < 50);

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
    public function uploadProfilePicture()
    {
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
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadImmagineCorrelata()
    {
        // check there is a profile pic to upload
        if ($this->getImmagineCorrelata() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getImmagineCorrelata()->move($this->getUploadRootDir(), $this->getImmagineCorrelataArticolo());

        // check if we have an old image
        if (isset($this->tempimmagine) && file_exists($this->getUploadRootDir().'/'.$this->tempimmagine)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempimmagine);
            // clear the temp image path
            $this->tempimmagine = null;
        }
        $this->immagineCorrelata = null;
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadAllegato1()
    {
        // check there is a profile pic to upload
        if ($this->getAllegato1File() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        if(!$this->getAllegato1File()->move($this->getUploadRootDir(), $this->getAllegato1())){

        }

        // check if we have an old image
        if (isset($this->tempimmagine) && file_exists($this->getUploadRootDir().'/'.$this->tempimmagine)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempimmagine);
            // clear the temp image path
            $this->tempimmagine = null;
        }
        $this->allegato1File = null;
    }


    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadAllegato2()
    {
        // check there is a profile pic to upload
        if ($this->getAllegato2File() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getAllegato2File()->move($this->getUploadRootDir(), $this->getAllegato2());

        // check if we have an old image
        if (isset($this->tempimmagine) && file_exists($this->getUploadRootDir().'/'.$this->tempimmagine)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempimmagine);
            // clear the temp image path
            $this->tempimmagine = null;
        }
        $this->allegato2File = null;
    }



    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadAllegato3()
    {
        // check there is a profile pic to upload
        if ($this->getAllegato3File() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getAllegato3File()->move($this->getUploadRootDir(), $this->getAllegato3());

        // check if we have an old image
        if (isset($this->tempimmagine) && file_exists($this->getUploadRootDir().'/'.$this->tempimmagine)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempimmagine);
            // clear the temp image path
            $this->tempimmagine = null;
        }
        $this->allegato3File = null;
    }



    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     *
     * Upload the profile picture
     *
     * @return mixed
     */
    public function uploadAllegato4()
    {
        // check there is a profile pic to upload
        if ($this->getAllegato4File() === null) {
            return;
        }
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getAllegato4File()->move($this->getUploadRootDir(), $this->getAllegato4());

        // check if we have an old image
        if (isset($this->tempimmagine) && file_exists($this->getUploadRootDir().'/'.$this->tempimmagine)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->tempimmagine);
            // clear the temp image path
            $this->tempimmagine = null;
        }
        $this->allegato4File = null;
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


    public function __toString()
    {
        return $this->titolo;
    }

    /**
     * @return mixed
     */
    public function getSottotitolo()
    {
        return $this->sottotitolo;
    }

    /**
     * @param mixed $sottotitolo
     */
    public function setSottotitolo($sottotitolo)
    {
        $this->sottotitolo = $sottotitolo;
    }

    /**
     * @return mixed
     */
    public function getImmagineCorrelata()
    {
        return $this->immagineCorrelata;
    }

    /**
     * @param mixed $immagineCorrelata
     */
    public function setImmagineCorrelata($immagineCorrelata)
    {
        $this->immagineCorrelata = $immagineCorrelata;
    }

    /**
     * @return mixed
     */
    public function getTitoloCorrelato()
    {
        return $this->titoloCorrelato;
    }

    /**
     * @param mixed $titoloCorrelato
     */
    public function setTitoloCorrelato($titoloCorrelato)
    {
        $this->titoloCorrelato = $titoloCorrelato;
    }

    /**
     * @return mixed
     */
    public function getTestoCorrelato()
    {
        return $this->testoCorrelato;
    }

    /**
     * @param mixed $testoCorrelato
     */
    public function setTestoCorrelato($testoCorrelato)
    {
        $this->testoCorrelato = $testoCorrelato;
    }

    /**
     * @return mixed
     */
    public function getLinkCorrelato()
    {
        return $this->linkCorrelato;
    }

    /**
     * @param mixed $linkCorrelato
     */
    public function setLinkCorrelato($linkCorrelato)
    {
        $this->linkCorrelato = $linkCorrelato;
    }

    /**
     * @return mixed
     */
    public function getImmagineCorrelataArticolo()
    {
        return $this->immagineCorrelataArticolo;
    }

    /**
     * @param mixed $immagineCorrelataArticolo
     */
    public function setImmagineCorrelataArticolo($immagineCorrelataArticolo)
    {
        $this->immagineCorrelataArticolo = $immagineCorrelataArticolo;
    }

    /**
     * @return mixed
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * @param mixed $gallery
     */
    public function setGallery($gallery)
    {
        $this->gallery = $gallery;
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


    public function getFeedItemTitle()
    {
        return $this->titolo;
    }

    public function getFeedItemDescription()
    {
        return $this->sottotitolo;
    }

    public function getFeedItemPubDate()
    {
        return $this->lastUpdateTimestamp;
    }

    public function getFeedItemLink()
    {
        if ($this->getCategoria() != null) {
            return "http://www.velaforfun.com/".$this->getCategoria()->getPermalink()."/".$this->getPermalink();

        }
    }

    /**
     * @return mixed
     */
    public function getAllegato1()
    {
        return $this->allegato1;
    }

    /**
     * @param mixed $allegato1
     */
    public function setAllegato1($allegato1)
    {
        $this->allegato1 = $allegato1;
    }

    /**
     * @return mixed
     */
    public function getAllegato2()
    {
        return $this->allegato2;
    }

    /**
     * @param mixed $allegato2
     */
    public function setAllegato2($allegato2)
    {
        $this->allegato2 = $allegato2;
    }

    /**
     * @return mixed
     */
    public function getAllegato3()
    {
        return $this->allegato3;
    }

    /**
     * @param mixed $allegato3
     */
    public function setAllegato3($allegato3)
    {
        $this->allegato3 = $allegato3;
    }

    /**
     * @return mixed
     */
    public function getAllegato4()
    {
        return $this->allegato4;
    }

    /**
     * @param mixed $allegato4
     */
    public function setAllegato4($allegato4)
    {
        $this->allegato4 = $allegato4;
    }

    /**
     * @return mixed
     */
    public function getAllegato1File()
    {
        return $this->allegato1File;
    }

    /**
     * @param mixed $allegato1File
     */
    public function setAllegato1File($allegato1File)
    {
        $this->allegato1File = $allegato1File;
    }

    /**
     * @return mixed
     */
    public function getAllegato2File()
    {
        return $this->allegato2File;
    }

    /**
     * @param mixed $allegato2File
     */
    public function setAllegato2File($allegato2File)
    {
        $this->allegato2File = $allegato2File;
    }

    /**
     * @return mixed
     */
    public function getAllegato3File()
    {
        return $this->allegato3File;
    }

    /**
     * @param mixed $allegato3File
     */
    public function setAllegato3File($allegato3File)
    {
        $this->allegato3File = $allegato3File;
    }

    /**
     * @return mixed
     */
    public function getAllegato4File()
    {
        return $this->allegato4File;
    }

    /**
     * @param mixed $allegato4File
     */
    public function setAllegato4File($allegato4File)
    {
        $this->allegato4File = $allegato4File;
    }


}
