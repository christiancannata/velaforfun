<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="foto")
 * @ORM\HasLifecycleCallbacks()
 */
class Foto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

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
     * @var string
     *
     * @ORM\Column(name="nome", type="string", nullable=true)
     */
    private $nome;


    /**
     * @var string
     *
     * @ORM\Column(name="autore", type="string", nullable=true)
     */
    private $autore;


    /**
     * @var string
     *
     * @ORM\Column(name="in_evidenza", type="boolean", nullable=true)
     */
    private $inEvidenza;

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
     * @ORM\ManyToOne(targetEntity="GalleriaFoto", inversedBy="foto",cascade={"remove"})
     * @ORM\JoinColumn(name="id_galleria", referencedColumnName="id", nullable=true)
     **/
    private $galleria;


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
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getInEvidenza()
    {
        return $this->inEvidenza;
    }

    /**
     * @param string $inEvidenza
     */
    public function setInEvidenza($inEvidenza)
    {
        $this->inEvidenza = $inEvidenza;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
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
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getGalleria()
    {
        return $this->galleria;
    }

    /**
     * @param mixed $galleria
     */
    public function setGalleria($galleria)
    {
        $this->galleria = $galleria;
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
            $this->tempProfilePicturePath = $this->immagine;
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
     * Set profilePicturePath
     *
     * @param string $profilePicturePath
     * @return User
     */
    public function setImmagine($profilePicturePath)
    {
        $this->immagine = $profilePicturePath;

        return $this;
    }

    /**
     * Get profilePicturePath
     *
     * @return string
     */
    public function getImmagine()
    {
        return $this->immagine;
    }

    /**
     * Get the absolute path of the profilePicturePath
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
        return 'uploads/galleria_foto';
    }

    /**
     * Get the web path for the user
     *
     * @return string
     */
    public function getWebProfilePicturePath()
    {

        return '/'.$this->getUploadDir().'/'.$this->getImmagine();
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
            $filename = str_replace(" ", "", $this->getNome())."-".$oggi->format("U");
            $this->setImmagine($filename.'.'.$this->getProfilePictureFile()->guessExtension());
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
        $this->getProfilePictureFile()->move($this->getUploadRootDir(), $this->getImmagine());

        // check if we have an old image
        if (isset($this->tempProfilePicturePath) && file_exists(
                $this->getUploadRootDir().'/'.$this->tempProfilePicturePath
            )
        ) {
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

    public function __toString()
    {
        return $this->nome;
    }

    /**
     * @return string
     */
    public function getAutore()
    {
        return $this->autore;
    }

    /**
     * @param string $autore
     */
    public function setAutore($autore)
    {
        $this->autore = $autore;
    }

}
