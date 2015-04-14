<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Segnale")
 */
class Segnale {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="lettera", type="string", nullable=true)
	 */
	private $lettera;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="morse", type="string", nullable=true)
	 */
	private $morse;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="parola", type="string", nullable=true)
	 */
	private $parola;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="valore", type="string", nullable=true)
	 */
	private $valore;

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
    public function getLettera()
    {
        return $this->lettera;
    }

    /**
     * @param string $lettera
     */
    public function setLettera($lettera)
    {
        $this->lettera = $lettera;
    }

    /**
     * @return string
     */
    public function getMorse()
    {
        return $this->morse;
    }

    /**
     * @param string $morse
     */
    public function setMorse($morse)
    {
        $this->morse = $morse;
    }

    /**
     * @return string
     */
    public function getParola()
    {
        return $this->parola;
    }

    /**
     * @param string $parola
     */
    public function setParola($parola)
    {
        $this->parola = $parola;
    }

    /**
     * @return string
     */
    public function getValore()
    {
        return $this->valore;
    }

    /**
     * @param string $valore
     */
    public function setValore($valore)
    {
        $this->valore = $valore;
    }




}
