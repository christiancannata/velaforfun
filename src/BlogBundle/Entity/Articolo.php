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

/**
 * @ORM\Entity
 * @ORM\Table(name="articolo")
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
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $immagine;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $tags;


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
     * @ORM\Column(type="integer")
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


    public function generatePermalink($string){
        $slug=preg_replace('/[^A-Za-z0-9-]+/', '-', $string);
        $this->permalink=$slug;
    }
}
