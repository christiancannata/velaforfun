<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="annuncio_imbarco")
 */
class AnnuncioImbarco
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
     * @ORM\Column(name="titolo", type="string", nullable=true)
     */
    private $titolo;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="tipo_annuncio", type="string", columnDefinition="ENUM('CERCO','OFFRO')", nullable=false)
     */
    private $tipoAnnuncio;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="luogo", type="string", columnDefinition="ENUM('NORD_ITALIA','SUD_ITALIA','CENTRO','ESTERO')", nullable=false)
     */
    private $luogo;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="tipo", type="string", columnDefinition="ENUM('CABINATO','DERIVA','ALTRO')", nullable=false)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="localita", type="string", nullable=true)
     */
    private $localita;

    /**
     * @var string
     *
     * @ORM\Column(name="tempo", type="string", nullable=true)
     */
    private $tempo;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="ruolo_richiesto", type="string", columnDefinition="ENUM('PRODIERE','UOMO_ALBERO','PITMAN','GRINDER','TAILER','TATTICO','RANDISTA','TIMONIERE','CUOCO','MOZZO_GENERICO','COMANDANTE','SECONDO','MOTORISTA','STEWARD','HOSTESS')", nullable=false)
     */
    private $ruoloRichiesto;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="costo", type="string", columnDefinition="ENUM('GRATIS','A_PAGAMENTO','PAGATO')", nullable=false)
     */
    private $costo;

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
     * @ORM\OneToMany(targetEntity="RispostaAnnuncioImbarco", mappedBy="annuncio")
     **/
    private $risposte;


    public function __construct() {
        $this->risposte = new ArrayCollection();
    }

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
    public function getLuogo()
    {
        return $this->luogo;
    }

    /**
     * @param string $luogo
     */
    public function setLuogo($luogo)
    {
        $this->luogo = $luogo;
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
    public function getLocalita()
    {
        return $this->localita;
    }

    /**
     * @param string $localita
     */
    public function setLocalita($localita)
    {
        $this->localita = $localita;
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
    public function getRuoloRichiesto()
    {
        return $this->ruoloRichiesto;
    }

    /**
     * @param string $ruoloRichiesto
     */
    public function setRuoloRichiesto($ruoloRichiesto)
    {
        $this->ruoloRichiesto = $ruoloRichiesto;
    }

    /**
     * @return string
     */
    public function getCosto()
    {
        return $this->costo;
    }

    /**
     * @param string $costo
     */
    public function setCosto($costo)
    {
        $this->costo = $costo;
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
    public function getTipoAnnuncio()
    {
        return $this->tipoAnnuncio;
    }

    /**
     * @param string $tipoAnnuncio
     */
    public function setTipoAnnuncio($tipoAnnuncio)
    {
        $this->tipoAnnuncio = $tipoAnnuncio;
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






}
