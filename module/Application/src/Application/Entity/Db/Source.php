<?php

namespace Application\Entity\Db;

class Source {
    const Octopus = 'OCTOPUS';
    const Preecog = 'PrEECoG';

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
}