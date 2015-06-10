<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="compatibilita_forum")
 */
class CompatibilitaForum {
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="id_old", type="integer", nullable=true)
	 */
	private $idOld;

    /**
     * @var string
     *
     * @ORM\Column(name="id_new", type="integer", nullable=true)
     */
    private $IdNew;

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
    public function getIdOld()
    {
        return $this->idOld;
    }

    /**
     * @param string $idOld
     */
    public function setIdOld($idOld)
    {
        $this->idOld = $idOld;
    }

    /**
     * @return string
     */
    public function getIdNew()
    {
        return $this->IdNew;
    }

    /**
     * @param string $IdNew
     */
    public function setIdNew($IdNew)
    {
        $this->IdNew = $IdNew;
    }




}
