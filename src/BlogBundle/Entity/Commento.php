<?php
// src/Blogger/BlogBundle/Entity/Blog.php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity
 * @ORM\Table(name="commento")
 */
class Commento
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
    protected $testo;


    /**
     * @ORM\Column(type="datetime")
     */
    protected $timestamp;

    /**
     * @ManyToOne(targetEntity="AppBundle\Entity\User")
     * @JoinColumn(name="id_autore", referencedColumnName="id")
     **/
    protected $autore;

    /**
     * @ManyToOne(targetEntity="Articolo")
     * @JoinColumn(name="id_articolo", referencedColumnName="id")
     **/
    protected $articolo;

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
    public function getArticolo()
    {
        return $this->articolo;
    }

    /**
     * @param mixed $articolo
     */
    public function setArticolo($articolo)
    {
        $this->articolo = $articolo;
    }

}
