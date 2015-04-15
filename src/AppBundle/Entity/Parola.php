<?php

namespace Acme\DemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parola")
 */
class Parola {
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
	private $parola;


	/**
	 * @var string
	 *
	 * @ORM\Column(name="morse", type="text", nullable=true)
	 */
	private $definizione;



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
    public function getDefinizione()
    {
        return $this->definizione;
    }

    /**
     * @param string $definizione
     */
    public function setDefinizione($definizione)
    {
        $this->definizione = $definizione;
    }


}
