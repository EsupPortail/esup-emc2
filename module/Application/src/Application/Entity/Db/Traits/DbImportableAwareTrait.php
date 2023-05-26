<?php

namespace Application\Entity\Db\Traits;

use DateTime;

trait DbImportableAwareTrait {

    private ?DateTime $created_on;
    private ?DateTime $updated_on;
    private ?DateTime $deleted_on;
    private ?string $sourceId = null;

    public function getDeleted() : ?DateTime
    {
        return $this->deleted_on;
    }

    public function isDeleted(DateTime $date = null) : bool
    {
        if ($date === null) return ($this->deleted_on !== null);
        return ($this->deleted_on !== null AND $this->deleted_on < $date);
    }

    public function setCreatedOn(DateTime $date = null)
    {
        if ($date === null) $date = new DateTime();
        $this->created_on = $date;
    }

    public function historise(?DateTime $now = null) : void
    {
        if ($now === null) {
            $now = new DateTime();
        }
        $this->deleted_on = $now;
    }

    public function dehistorise(?DateTime $now = null) : void
    {
        if ($now === null) {
            $now = new DateTime();
        }
        $this->updated_on = $now;
        $this->deleted_on = null;
    }

    public function getSourceId() : ?string
    {
        return $this->sourceId;
    }

}