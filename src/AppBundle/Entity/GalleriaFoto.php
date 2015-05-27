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

/**
 * @ORM\Entity
 * @ORM\Table(name="galleria_foto")
 */
class GalleriaFoto
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
     * @ORM\OneToMany(targetEntity="Foto", mappedBy="galleria")
     **/
    private $foto;




    /**
     * @ORM\OneToOne(targetEntity="BlogBundle\Entity\Articolo")
     * @ORM\JoinColumn(name="id_articolo", referencedColumnName="id", nullable=true)
     **/
    private $articolo;

    public function __construct()
    {
        $this->foto = new ArrayCollection();
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
    public function getFoto()
    {
        return $this->nodi;
    }

    /**
     * @param mixed $nodi
     */
    public function setFoto($nodi)
    {
        $this->foto = $nodi;
    }


    public function addFoto(Foto $nodo)
    {
        $this->foto->addElement($nodo);
    }

    public function removeFoto(Foto $nodo)
    {
        $this->foto->removeElement($nodo);
    }

    public function __toString(){
        return $this->id."-".$this->nome;
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
