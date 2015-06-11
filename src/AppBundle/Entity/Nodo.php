<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="nodo")
 * @ORM\HasLifecycleCallbacks()
 */
class Nodo
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
     * @ORM\Column(name="nome", type="string", nullable=false)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", nullable=false)
     */
    private $permalink;


    /**
     * @var string
     *
     * @ORM\Column(name="video", type="string", nullable=false)
     */
    private $video;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text", nullable=true)
     */
    private $descrizione;

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
     * @Assert\Image(mimeTypesMessage="Please upload a valid image.")
     */
    protected $immagineCorrelata;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $immagineCorrelataArticolo;


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
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * @param string $permalink
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
    }

    /**
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * @param string $video
     */
    public function setVideo($video)
    {
        $this->video = $video;
    }

    /**
     * @return string
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * @param string $descrizione
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;
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
    public function preUploadImmagineCorrelata()
    {
        if (null !== $this->getImmagineCorrelata()) {
            // a file was uploaded
            // generate a unique filename
            $oggi = new \DateTime();
            $filename = str_replace(
                '"',
                '',
                str_replace("''", "", str_replace(" ", "-", $this->getNome())."-".$oggi->format("U"))
            );
            $this->setImmagineCorrelataArticolo($filename.'.'.$this->getImmagineCorrelata()->guessExtension());
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
        return 'images/nodi';
    }


    public function __toString()
    {
        return $this->nome;
    }

}
