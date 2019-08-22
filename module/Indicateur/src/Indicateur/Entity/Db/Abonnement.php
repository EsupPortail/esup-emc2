<?php

namespace Indicateur\Entity\Db;

use DateTime;
use Utilisateur\Entity\Db\User;

class Abonnement {

    /** @var integer */
    private $id;
    /** @var User */
    private $user;
    /** @var Indicateur */
    private $indicateur;
    /** @var string */
    private $frequence;
    /** @var DateTime */
    private $denierEnvoi;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Abonnement
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Indicateur
     */
    public function getIndicateur()
    {
        return $this->indicateur;
    }

    /**
     * @param Indicateur $indicateur
     * @return Abonnement
     */
    public function setIndicateur($indicateur)
    {
        $this->indicateur = $indicateur;
        return $this;
    }

    /**
     * @return string
     */
    public function getFrequence()
    {
        return $this->frequence;
    }

    /**
     * @param string $frequence
     * @return Abonnement
     */
    public function setFrequence($frequence)
    {
        $this->frequence = $frequence;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDenierEnvoi()
    {
        return $this->denierEnvoi;
    }

    /**
     * @param DateTime $denierEnvoi
     * @return Abonnement
     */
    public function setDenierEnvoi($denierEnvoi)
    {
        $this->denierEnvoi = $denierEnvoi;
        return $this;
    }


}