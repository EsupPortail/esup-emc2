<?php

namespace Metier\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Referentiel implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const WEB="web";
    const PDF="pdf";
    const VIDE="vide";

    /** @var integer */
    private $id;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $prefix;
    /** @var string */
    private $type;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Referentiel
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @param string $libelleCourt
     * @return Referentiel
     */
    public function setLibelleCourt(string $libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * @param string $libelleLong
     * @return Referentiel
     */
    public function setLibelleLong(string $libelleLong)
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     * @return Referentiel
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Referentiel
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }



}