<?php

namespace Referentiel\Entity\Db;

use DateTime;

trait IsSynchronisableTrait {

    private ?Datetime $createdOn;
    private ?Datetime $updatedOn;
    private ?Datetime $deletedOn;

    public function getInsertedOn(): ?DateTime
    {
        return $this->createdOn;
    }

    public function setInsertedOn(?DateTime $createdOn): void
    {
        $this->createdOn = $createdOn;
    }

    public function getUpdatedOn(): ?DateTime
    {
        return $this->updatedOn;
    }

    public function setUpdatedOn(?DateTime $updatedOn): void
    {
        $this->updatedOn = $updatedOn;
    }

    public function getDeletedOn(): ?DateTime
    {
        return $this->deletedOn;
    }

    public function setDeletedOn(?DateTime $deletedOn): void
    {
        $this->deletedOn = $deletedOn;
    }

    public function isDeleted(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        if ($this->deletedOn === null OR $this->deletedOn > $date) return false;
        return true;
    }

}