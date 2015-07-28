<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="pagina_statica")
 * @ORM\HasLifecycleCallbacks()
 */
class PaginaStatica
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
     * @ORM\Column(name="titolo", type="string", nullable=false)
     */
    private $titolo;


    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="string", nullable=false)
     */
    private $descrizione;


    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


    /**
     * @var string
     *
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive;


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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $titoloCorrelato;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $testoCorrelato;
    // for temporary storage
    private $tempimmagine;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $linkCorrelato;


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
    public function getTitolo()
    {
        return $this->titolo;
    }

    /**
     * @param string $titolo
     */
    public function setTitolo($titolo)
    {
        $this->titolo = $titolo;
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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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

    public function __toString()
    {
        return $this->nome;
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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUploadImmagineCorrelata() {
        if (null !== $this->getImmagineCorrelata()) {
            // a file was uploaded
            // generate a unique filename
            $oggi=new \DateTime();
            $filename=str_replace('"','',str_replace("''","",str_replace(" ","-",$this->getTitoloCorrelato())."-".$oggi->format("U")));
            $this->setImmagineCorrelataArticolo($filename.'.'.$this->getImmagineCorrelata()->guessExtension());
        }
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
     * @return string
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param string $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }



}
