<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class MetierReferentiel implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const WEB="web";
    const PDF="pdf";


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
     * @return MetierReferentiel
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
     * @return MetierReferentiel
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
     * @return MetierReferentiel
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
     * @return MetierReferentiel
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
     * @return MetierReferentiel
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }



}