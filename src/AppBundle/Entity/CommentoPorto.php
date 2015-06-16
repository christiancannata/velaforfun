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
 * @ORM\Table(name="commento_porto")
 */
class CommentoPorto {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="testo", type="text", nullable=false)
     */
    private $testo;


    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="id_utente", referencedColumnName="id", nullable=true)
     **/
    private $utente;


    /**
     * @ORM\ManyToOne(targetEntity="Porto", inversedBy="commenti")
     * @ORM\JoinColumn(name="id_porto", referencedColumnName="id", nullable=true)
     **/
    private $porto;


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
     * @ORM\Column(name="tipo_commento", type="string", columnDefinition="ENUM('POSITIVO','NEUTRO','NEGATIVO')", nullable=false)
     */
    private $tipoCommento;



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
    public function getTesto()
    {
        return $this->testo;
    }

    /**
     * @param string $testo
     */
    public function setTesto($testo)
    {
        $this->testo = $testo;
    }

    /**
     * @return mixed
     */
    public function getUtente()
    {
        return $this->utente;
    }

    /**
     * @param mixed $utente
     */
    public function setUtente($utente)
    {
        $this->utente = $utente;
    }

    /**
     * @return mixed
     */
    public function getPorto()
    {
        return $this->porto;
    }

    /**
     * @param mixed $porto
     */
    public function setPorto($porto)
    {
        $this->porto = $porto;
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
     * @return string
     */
    public function getTipoCommento()
    {
        return $this->tipoCommento;
    }

    /**
     * @param string $tipoCommento
     */
    public function setTipoCommento($tipoCommento)
    {
        $this->tipoCommento = $tipoCommento;
    }


}
