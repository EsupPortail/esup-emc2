<?php

namespace Mailing\Model\Db;

use DateTime;

class Mail {

    const PENDING = 0;
    const SUCCESS = 1;
    const FAILURE = 2;


    /** @var integer */
    private $id;
    /** @var DateTime */
    private $dateEnvoi;
    /** @var int */
    private $statusEnvoi;
    /** @var string */
    private $destinataires;
    /** @var boolean */
    private $redirection;
    /** @var string */
    private $sujet;
    /** @var string */
    private $corps;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * @param DateTime $dateEnvoi
     * @return Mail
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatusEnvoi()
    {
        return $this->statusEnvoi;
    }

    /**
     * @param int $statusEnvoi
     * @return Mail
     */
    public function setStatusEnvoi($statusEnvoi)
    {
        $this->statusEnvoi = $statusEnvoi;
        return $this;
    }

    /**
     * @return string
     */
    public function getDestinatires()
    {
        return $this->destinataires;
    }

    /**
     * @param string $destinataires
     * @return Mail
     */
    public function setDestinatires($destinataires)
    {
        $this->destinataires = $destinataires;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRedirection()
    {
        return $this->redirection;
    }

    /**
     * @param int $redirection
     * @return Mail
     */
    public function setRedir($redirection)
    {
        $this->redirection = $redirection;
        return $this;
    }

    /**
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * @param string $sujet
     * @return Mail
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
        return $this;
    }

    /**
     * @return string
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * @param string $corps
     * @return Mail
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;
        return $this;
    }

}