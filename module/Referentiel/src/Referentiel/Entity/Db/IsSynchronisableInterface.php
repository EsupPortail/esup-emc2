<?php

namespace Referentiel\Entity\Db;

use DateTime;

interface IsSynchronisableInterface {

    public function getCreatedOn() : ?DateTime;
    public function getUpdatedOn() : ?DateTime;
    public function getDeletedOn() : ?DateTime;
    public function isDeleted(?DateTime $date = null) : bool;

}