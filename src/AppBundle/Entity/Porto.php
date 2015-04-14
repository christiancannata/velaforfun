<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Porto")
 */
class Parola
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
     * @ORM\Column(name="email", type="simple_array", nullable=true)
     */
    private $email;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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


}
