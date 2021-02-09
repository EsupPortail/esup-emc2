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

    public function isDeleted(DateTime $date = null) : bool
    {
        if ($date === null) return ($this->deleted_on !== null);
        return $this->deleted_on < $date;
    }
}