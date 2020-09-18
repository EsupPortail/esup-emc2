<?php

namespace UnicaenDocument\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Content implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const TYPE_TXT = 'texte';
    const TYPE_PDF = 'pdf';

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $description;
    /** @var string */
    private $type;
    /** @var string */
    private $complement;
    /** @var string */
    private $content;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Content
     */
    public function setId(int $id): Content
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Content
     */
    public function setCode(string $code): Content
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Content
     */
    public function setDescription(string $description): Content
    {
        $this->description = $description;
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
     * @return Content
     */
    public function setType(string $type): Content
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplement(): string
    {
        return $this->complement;
    }

    /**
     * @param string $complement
     * @return Content
     */
    public function setComplement(string $complement): Content
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Content
     */
    public function setContent(string $content): Content
    {
        $this->content = $content;
        return $this;
    }

}