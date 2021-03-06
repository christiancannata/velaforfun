<?php
// src/Blogger/BlogBundle/Entity/Blog.php

namespace BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
/**
 * @ORM\Entity
 * @ORM\Table(name="categoria")
 * @ORM\HasLifecycleCallbacks()
 */
class Categoria
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
    protected $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", nullable=true)
     */
    private $permalink;

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
     * @var string
     *
     * @ORM\Column(name="colore", type="string", nullable=true)
     */
    private $colore;



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
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * @param mixed $nome
     */
    public function setNome($nome)
    {
        $this->nome = $nome;
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
     * @ORM\PrePersist
     */
    public function generatePermalink()
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', $this->nome."-".rand(1, 99));
        $this->permalink = strtolower($slug);
    }


    public function __toString(){
        return $this->id." - ".$this->nome;
    }

    /**
     * @return string
     */
    public function getColore()
    {
        return $this->colore;
    }

    /**
     * @param string $colore
     */
    public function setColore($colore)
    {
        $this->colore = $colore;
    }


}
