<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

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
