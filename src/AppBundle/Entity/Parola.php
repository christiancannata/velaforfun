<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

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
