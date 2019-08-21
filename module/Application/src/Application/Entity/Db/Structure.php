<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Utilisateur\Entity\Db\User;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class Structure implements ResourceInterface{
    use ImportableAwareTrait;

    public function getResourceId()
    {
        return 'structure';
    }

    /** @var string */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $type;
    /** @var string */
    private $histo;


    /** @var string */
    private $description;
    /** @var ArrayCollection */
    private $gestionnaires;

    public function __construct()
    {
        $this->gestionnaires = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Structure
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * @return Structure
     */
    public function setLibelleCourt($libelleCourt)
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
     * @return Structure
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
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
     * @return Structure
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Structure
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return User[]
     */
    public function getGestionnaires()
    {
        return $this->gestionnaires->toArray();
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function addGestionnaire($user)
    {
        $this->gestionnaires->add($user);
        return $this;
    }

    /**
     * @param User $user
     * @return Structure
     */
    public function removeGestionnaire($user)
    {
        $this->gestionnaires->removeElement($user);
        return $this;
    }

    /**
     * @return string
     */
    public function getHisto()
    {
        return $this->histo;
    }

    /**
     * @param string $histo
     * @return Structure
     */
    public function setHisto($histo)
    {
        $this->histo = $histo;
        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $text =  "";
        $text .= "[".$this->getType()."] ";
        $text .= $this->getLibelleCourt();
        return $text;
    }
}