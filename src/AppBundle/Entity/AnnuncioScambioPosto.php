<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="annuncio_scambio_posto")
 */
class AnnuncioScambioPosto
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="id_utente", referencedColumnName="id")
     **/
    protected $utente;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", nullable=true)
     */
    private $telefono;

    /**
     * @ORM\ManyToOne(targetEntity="Porto", inversedBy="annunciScambioPosto")
     * @ORM\JoinColumn(name="id_porto_attuale", referencedColumnName="id", nullable=true)
     **/
    private $luogoAttuale;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="luogo_ricercato", type="string", columnDefinition="ENUM('NORD_ITALIA_TIRRENO','NORD_ITALIA_ADRIATICO','CENTRO_ITALIA_TIRRENO','CENTRO_ITALIA_ADRIATICO','SUD_ITALIA_TIRRENO','SUD_ITALIA_ADRIATICO','SARDEGNA','SICILIA','ESTERO')", nullable=false)
     */
    private $luogoRicercato;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="tipo", type="string", columnDefinition="ENUM('VELA','MOTORE','ALTRO')", nullable=false)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="tempo", type="string", nullable=true)
     */
    private $tempo;

    /**
     * @var string
     *
     * @ORM\Column(name="lunghezza", type="float", nullable=true)
     */
    private $lunghezza;


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
     * @var string
     *
     * @ORM\Column(name="permalink", type="string", nullable=true)
     */
    private $permalink;

    /**
     * @ORM\OneToMany(targetEntity="RispostaAnnuncioScambioPosto", mappedBy="annuncio")
     **/
    private $risposte;

    /**
     * @ORM\ManyToOne(targetEntity="CCDNForum\ForumBundle\Entity\Topic")
     * @ORM\JoinColumn(name="id_topic", referencedColumnName="id", nullable=true)
     **/
    private $topic;


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
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * @param string $telefono
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    /**
     * @return string
     */
    public function getLuogoAttuale()
    {
        return $this->luogoAttuale;
    }

    /**
     * @param string $luogoAttuale
     */
    public function setLuogoAttuale($luogoAttuale)
    {
        $this->luogoAttuale = $luogoAttuale;
    }

    /**
     * @return string
     */
    public function getLuogoRicercato()
    {
        return $this->luogoRicercato;
    }

    /**
     * @param string $luogoRicercato
     */
    public function setLuogoRicercato($luogoRicercato)
    {
        $this->luogoRicercato = $luogoRicercato;
    }

    /**
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * @param string $tipo
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * @return string
     */
    public function getTempo()
    {
        return $this->tempo;
    }

    /**
     * @param string $tempo
     */
    public function setTempo($tempo)
    {
        $this->tempo = $tempo;
    }

    /**
     * @return string
     */
    public function getLunghezza()
    {
        return $this->lunghezza;
    }

    /**
     * @param string $lunghezza
     */
    public function setLunghezza($lunghezza)
    {
        $this->lunghezza = $lunghezza;
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
     * @return mixed
     */
    public function getRisposte()
    {
        return $this->risposte;
    }

    /**
     * @param mixed $risposte
     */
    public function setRisposte($risposte)
    {
        $this->risposte = $risposte;
    }

    /**
     * @return mixed
     */
    public function getTopic()
    {
        return $this->topic;
    }

    /**
     * @param mixed $topic
     */
    public function setTopic($topic)
    {
        $this->topic = $topic;
    }






}
