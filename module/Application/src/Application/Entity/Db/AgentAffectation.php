<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Structure\Entity\Db\Structure;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentAffectation implements HasPeriodeInterface {
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    /** @var integer */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Structure */
    private $structure;
    /** @var string */
    private $idOrig;
    /** @var string */
    private $principale;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @return Structure
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * @return string
     */
    public function getIdOrig()
    {
        return $this->idOrig;
    }

    /**
     * @return boolean
     */
    public function isPrincipale()
    {
        return ($this->principale === 'O');
    }

    /**
     * @param AgentAffectation[] $agentAffectations
     * @return string[]
     */
    public static function generateAffectationsArray(array $agentAffectations) : array
    {
        $affectations = [];
        foreach ($agentAffectations as $agentAffectation) {
            $structure = $agentAffectation->getStructure();
            $niveau2 = null;
            if ($structure) $niveau2 = $structure->getNiv2();
            if ($structure) {
                $texte = $structure->getLibelleCourt();
                if ($niveau2 !== null AND $niveau2 !== $structure) $texte = $niveau2->getLibelleCourt() . " > ".$texte;
                $affectations[] = $texte;
            } else {
                $affectations[] = "Structure inconnue";
            }
        }
        return $affectations;
    }
}