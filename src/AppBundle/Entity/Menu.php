<?php
/**
 * Created by PhpStorm.
 * User: christiancannata
 * Date: 05/05/15
 * Time: 14:35
 */

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="menu")
 */
class Menu
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
     * @ORM\Column(name="nome", type="string", nullable=true)
     */
    private $nome;

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
     * @ORM\OneToMany(targetEntity="NodoMenu", mappedBy="menu")
     **/
    private $nodi;

    public function __construct()
    {
        $this->nodi = new ArrayCollection;
    }  // your own logic

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
    public function getNodi()
    {
        return $this->nodi;
    }

    /**
     * @param mixed $nodi
     */
    public function setNodi($nodi)
    {
        $this->nodi = $nodi;
    }


    public function addNodo(NodoMenu $nodo)
    {
        $this->nodi->addElement($nodo);
    }

    public function removeNodo(NodoMenu $nodo)
    {
        $this->nodi->removeElement($nodo);
    }

    public function __toString(){
        return $this->nome;
    }


}
