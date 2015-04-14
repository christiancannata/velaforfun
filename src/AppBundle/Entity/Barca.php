<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="barca")
 */
class Barca {
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
	 * @ORM\Column(name="cantiere", type="string", nullable=true)
	 */
	private $cantiere;

    /**
     * @var string
     *
     * @ORM\Column(name="testo", type="text", nullable=true)
     */
    private $testo;

    /**
     * @var string
     *
     * @ORM\Column(name="web", type="text", nullable=true)
     */
    private $web;

	public function __construct() {
		parent::__construct();
		// your own logic
	}

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId() {
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
    public function getCantiere()
    {
        return $this->cantiere;
    }

    /**
     * @param string $cantiere
     */
    public function setCantiere($cantiere)
    {
        $this->cantiere = $cantiere;
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
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param string $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }



}
