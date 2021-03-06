<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="porto")
 * @ORM\HasLifecycleCallbacks()
 */
class Porto
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
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", nullable=true)
     */
    private $permalink;

    /**
     * @var string
     *
     * @ORM\Column(name="regione", type="text", nullable=true)
     */
    private $regione;

    /**
     * @var string
     *
     * @ORM\Column(name="latitudine", type="float", nullable=true)
     */
    private $latitudine;

    /**
     * @var string
     *
     * @ORM\Column(name="longitudine", type="float", nullable=true)
     */
    private $longitudine;

    /**
     * @var string
     *
     * @ORM\Column(name="dati_over", type="text", nullable=true)
     */
    private $datiOver;

    /**
     * @var string
     *
     * @ORM\Column(name="posti_totale", type="integer", nullable=true)
     */
    private $postiTotale;

    /**
     * @var string
     *
     * @ORM\Column(name="posti_transito", type="integer", nullable=true)
     */
    private $postiTransito;


    /**
     * @var string
     *
     * @ORM\Column(name="id_originale", type="integer", nullable=true)
     */
    private $idOriginale;


    /**
     * @var string
     *
     * @ORM\Column(name="email", type="text", nullable=true)
     */
    private $email;



    /**
     * @ORM\OneToMany(targetEntity="Attracco", mappedBy="porto")
     **/
    private $attracchi;



    /**
     * @ORM\OneToMany(targetEntity="CommentoPorto", mappedBy="porto")
     **/
    private $commenti;


    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="porto")
     **/
    private $utentiAttraccati;

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
     * @ORM\OneToMany(targetEntity="AnnuncioScambioPosto", mappedBy="luogoAttuale")
     **/
    protected $annunciScambioPosto;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
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
    public function getRegione()
    {
        return $this->regione;
    }

    /**
     * @param string $regione
     */
    public function setRegione($regione)
    {
        $this->regione = $regione;
    }

    /**
     * @return string
     */
    public function getLatitudine()
    {
        return $this->latitudine;
    }

    /**
     * @param string $latitudine
     */
    public function setLatitudine($latitudine)
    {
        $this->latitudine = $latitudine;
    }

    /**
     * @return string
     */
    public function getLongitudine()
    {
        return $this->longitudine;
    }

    /**
     * @param string $longitudine
     */
    public function setLongitudine($longitudine)
    {
        $this->longitudine = $longitudine;
    }

    /**
     * @return string
     */
    public function getDatiOver()
    {
        return $this->datiOver;
    }

    /**
     * @param string $datiOver
     */
    public function setDatiOver($datiOver)
    {
        $this->datiOver = $datiOver;
    }

    /**
     * @return string
     */
    public function getPostiTotale()
    {
        return $this->postiTotale;
    }

    /**
     * @param string $postiTotale
     */
    public function setPostiTotale($postiTotale)
    {
        $this->postiTotale = $postiTotale;
    }

    /**
     * @return string
     */
    public function getPostiTransito()
    {
        return $this->postiTransito;
    }

    /**
     * @param string $postiTransito
     */
    public function setPostiTransito($postiTransito)
    {
        $this->postiTransito = $postiTransito;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
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
     * @return mixed
     */
    public function getCommenti()
    {
        return $this->commenti;
    }

    /**
     * @param mixed $commenti
     */
    public function setCommenti($commenti)
    {
        $this->commenti = $commenti;
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

    public function __toString(){
        return $this->id."-".$this->nome;
    }


    /**
     * @ORM\PrePersist
     */
    public function generatePermalink()
    {
        $slug = preg_replace('/[^A-Za-z0-9-]+/', '-', str_replace(" ","-",$this->nome));
        $this->permalink = strtolower($slug."-".rand(1, 99));
    }

    /**
     * @return mixed
     */
    public function getUtentiAttraccati()
    {
        return $this->utentiAttraccati;
    }

    /**
     * @return mixed
     */
    public function getAttracchi()
    {
        return $this->attracchi;
    }

    /**
     * @param mixed $attracchi
     */
    public function setAttracchi($attracchi)
    {
        $this->attracchi = $attracchi;
    }

    /**
     * @return mixed
     */
    public function getAnnunciScambioPosto()
    {
        return $this->annunciScambioPosto;
    }

    /**
     * @param mixed $annunciScambioPosto
     */
    public function setAnnunciScambioPosto($annunciScambioPosto)
    {
        $this->annunciScambioPosto = $annunciScambioPosto;
    }

    /**
     * @return string
     */
    public function getIdOriginale()
    {
        return $this->idOriginale;
    }

    /**
     * @param string $idOriginale
     */
    public function setIdOriginale($idOriginale)
    {
        $this->idOriginale = $idOriginale;
    }


}
