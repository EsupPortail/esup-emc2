<?php

namespace Mailing\Model\Db;

use DateTime;

class Mail {

    //todo referencer directement les etats (quid du lien avec UnicaenEtat)
    const PENDING = 14;
    const SUCCESS = 15;
    const FAILURE = 16;

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

    /** @var int */
    private $mailtype_id;

    /**
     * L'ATTACHEMENT CORRESPOND A UNE ENTITE (TYPE + ID) LIE À L'ENVOI DU MAIL
     * PAR EXEMPLE : TYPE=EntretienProfessionnel::class ID=123
     */
    /** @var string */
    private $attachementType;
    /** @var int */
    private $attachementId;


    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return DateTime|null
     */
    public function getDateEnvoi() : ?DateTime
    {
        return $this->dateEnvoi;
    }

    /**
     * @param DateTime|null $dateEnvoi
     * @return Mail
     */
    public function setDateEnvoi(?DateTime $dateEnvoi) : Mail
    {
        $this->dateEnvoi = $dateEnvoi;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getStatusEnvoi() : ?int
    {
        return $this->statusEnvoi;
    }

    /**
     * @param int|null $statusEnvoi
     * @return Mail
     */
    public function setStatusEnvoi(?int $statusEnvoi) : Mail
    {
        $this->statusEnvoi = $statusEnvoi;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDestinatires() : ?string
    {
        return $this->destinataires;
    }

    /**
     * @param string|null $destinataires
     * @return Mail
     */
    public function setDestinatires(?string $destinataires) : Mail
    {
        $this->destinataires = $destinataires;
        return $this;
    }

    /**
     * @return bool
     */
    public function isRedirection() : bool
    {
        return $this->redirection;
    }

    /**
     * @param bool|null $redirection
     * @return Mail
     */
    public function setRedir(?bool $redirection) : Mail
    {
        $this->redirection = $redirection;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSujet() : ?string
    {
        return $this->sujet;
    }

    /**
     * @param string|null $sujet
     * @return Mail
     */
    public function setSujet(?string $sujet) : Mail
    {
        $this->sujet = $sujet;
        return $this;
    }

    /**
     * @return string | null
     */
    public function getCorps() : ?string
    {
        return $this->corps;
    }

    /**
     * @param string|null $corps
     * @return Mail
     */
    public function setCorps(?string $corps) : Mail
    {
        $this->corps = $corps;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMailtypeId(): ?int
    {
        return $this->mailtype_id;
    }

    /**
     * @param int|null $mailtype_id
     * @return Mail
     */
    public function setMailtypeId(?int $mailtype_id): Mail
    {
        $this->mailtype_id = $mailtype_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttachementType(): ?string
    {
        return $this->attachementType;
    }

    /**
     * @param string|null $attachementType
     * @return Mail
     */
    public function setAttachementType(?string $attachementType): Mail
    {
        $this->attachementType = $attachementType;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAttachementId(): ?int
    {
        return $this->attachementId;
    }

    /**
     * @param int|null $attachementId
     * @return Mail
     */
    public function setAttachementId(?int $attachementId): Mail
    {
        $this->attachementId = $attachementId;
        return $this;
    }
}