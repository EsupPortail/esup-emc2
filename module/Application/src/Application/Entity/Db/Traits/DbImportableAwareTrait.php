<?php

namespace Application\Entity\Db\Traits;

use DateTime;

trait DbImportableAwareTrait {

    /** @var DateTime */
    private $created_on;
    /** @var DateTime */
    private $updated_on;
    /** @var DateTime */
    private $deleted_on;

    public function getDeleted() { return $this->deleted_on; }

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

}