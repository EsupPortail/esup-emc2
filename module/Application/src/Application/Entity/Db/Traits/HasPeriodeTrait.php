<?php

namespace Application\Entity\Db\Traits;

use DateTime;
use Doctrine\ORM\QueryBuilder;

trait HasPeriodeTrait {

    /** @var DateTime|null */
    private $dateDebut;
    /** @var DateTime|null */
    private $dateFin;

    /**
     * @return DateTime|null
     */
    public function getDateDebut() : ?DateTime
    {
        return $this->dateDebut;
    }

    /**
     * @param DateTime|null $date
     * @return self
     */
    public function setDateDebut(?DateTime $date) : self
    {
        $this->dateDebut = $date;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDateFin() : ?DateTime
    {
        return $this->dateFin;
    }

    /**
     * @param DateTime|null $date
     * @return self
     */
    public function setDateFin(?DateTime $date) : self
    {
        $this->dateFin = $date;
        return $this;
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estCommence(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->dateDebut <= $date);
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estFini(?DateTime $date = null) : bool
    {
        if ($date === null) $date = new DateTime();
        return ($this->dateFin !== null AND $this->dateFin < $date) ;
    }

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estEnCours(?DateTime $date = null) : bool
    {
        return ($this->estCommence($date) AND !$this->estFini($date));
    }

    /** Fonctions pour les macros *************************************************************************************/

    public function getDateDebutToString() : string
    {
        return ($this->dateDebut)?$this->dateDebut->format('d/m/Y'):"N.C.";
    }

    public function getDateFinToString() : string
    {
        return ($this->dateFin)?$this->dateFin->format('d/m/Y'):"N.C.";
    }

    /** Decorateur ****************************************************************************************************/

    /**
     * @param QueryBuilder $qb
     * @param string $entityName
     * @param DateTime|null $date
     * @return QueryBuilder
     */
    static public function decorateWithActif(QueryBuilder $qb, string $entityName,  ?DateTime $date = null) : QueryBuilder
    {
        if ($date === null) $date = new DateTime();
        $qb = $qb
            ->andWhere($entityName . '.dateDebut IS NULL OR ' . $entityName . '.dateDebut <= :date')
            ->andWhere($entityName . '.dateFin IS NULL OR ' . $entityName . '.dateFin >= :date')
            ->setParameter('date', $date);
        return $qb;
    }
}