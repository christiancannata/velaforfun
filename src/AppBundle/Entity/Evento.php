<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;


/**
 * @ORM\Entity
 * @ORM\Table(name="evento")
 */
class Evento {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
     * @Assert\NotBlank()
	 * @ORM\Column(name="link", type="string", nullable=true)
	 */
	private $link;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="nome", type="string", nullable=true)
     */
    private $nome;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="in_evidenza", type="boolean", nullable=true)
	 */
	private $inEvidenza;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_inizio", type="datetime", nullable=false)
     * @Serializer\Type("DateTime")
     */
    protected $dataInizio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="data_fine", type="datetime", nullable=false)
     * @Serializer\Type("DateTime")
     */
    protected $dataFine;


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


	public function __construct() {
		// your own logic
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
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getInEvidenza()
    {
        return $this->inEvidenza;
    }

    /**
     * @param string $inEvidenza
     */
    public function setInEvidenza($inEvidenza)
    {
        $this->inEvidenza = $inEvidenza;
    }

    /**
     * @return string
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param string $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
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
     * @return \DateTime
     */
    public function getDataFine()
    {
        return $this->dataFine;
    }

    /**
     * @param \DateTime $dataFine
     */
    public function setDataFine($dataFine)
    {
        $this->dataFine = $dataFine;
    }

    /**
     * @return \DateTime
     */
    public function getDataInizio()
    {
        return $this->dataInizio;
    }

    /**
     * @param \DateTime $dataInizio
     */
    public function setDataInizio($dataInizio)
    {
        $this->dataInizio = $dataInizio;
    }







}
