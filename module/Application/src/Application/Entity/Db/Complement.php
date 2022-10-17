<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Complement implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const COMPLEMENT_TYPE_STRUCTURE     = "STRUCTURE_FINE";
    const COMPLEMENT_TYPE_RESPONSABLE   = "RESPONSABLE_HIERARCHIQUE";
    const COMPLEMENT_TYPE_AUTORITE      = "AUTORITE_HIERARCHIQUE";

    /** @var int */
    private $id;
    /** @var string */
    private $attachmentType;
    /** @var string */
    private $attachmentId;
    /** @var string */
    private $type;
    /** @var string|null */
    private $complementType;
    /** @var string|null */
    private $complementId;
    /** @var string|null */
    private $complementText;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAttachmentType(): string
    {
        return $this->attachmentType;
    }

    /**
     * @param string $attachmentType
     * @return Complement
     */
    public function setAttachmentType(string $attachmentType): Complement
    {
        $this->attachmentType = $attachmentType;
        return $this;
    }

    /**
     * @return string
     */
    public function getAttachmentId(): string
    {
        return $this->attachmentId;
    }

    /**
     * @param string $attachmentId
     * @return Complement
     */
    public function setAttachmentId(string $attachmentId): Complement
    {
        $this->attachmentId = $attachmentId;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Complement
     */
    public function setType(string $type): Complement
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplementType(): ?string
    {
        return $this->complementType;
    }

    /**
     * @param string|null $complementType
     * @return Complement
     */
    public function setComplementType(?string $complementType): Complement
    {
        $this->complementType = $complementType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplementId(): ?string
    {
        return $this->complementId;
    }

    /**
     * @param string|null $complementId
     * @return Complement
     */
    public function setComplementId(?string $complementId): Complement
    {
        $this->complementId = $complementId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplementText(): ?string
    {
        return $this->complementText;
    }

    /**
     * @param string|null $complementText
     * @return Complement
     */
    public function setComplementText(?string $complementText): Complement
    {
        $this->complementText = $complementText;
        return $this;
    }

}