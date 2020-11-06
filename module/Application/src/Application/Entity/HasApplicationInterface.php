<?php

namespace Application\Entity;

use Application\Entity\Db\Application;

interface HasApplicationInterface {

    public function getApplication();
    public function setApplication(?Application $application);
}
