<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use RuntimeException;
use Structure\Entity\Db\Structure;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentAffectation implements HasPeriodeInterface {
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?Structure $structure = null;
    private ?string $principale = null;
    private ?string $hierarchique = null;
    private ?string $fonctionnelle = null;
    private ?int $quotite = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    public function getStructure() : ?Structure
    {
        return $this->structure;
    }

    /** Gestion des témoins ***************************************************************************************/

    const TEMOINS = [
        'principale', 'hierarchique', 'fonctionnelle'
    ];

    public function getTemoin(string $temoin) : bool
    {
        return match ($temoin) {
            'principale' => $this->isPrincipale(),
            'hierarchique' => $this->isHierarchique(),
            'fonctionnelle' => $this->isFonctionnelle(),
            default => throw new RuntimeException("Le temoin [" . $temoin . "] est inconnu ou non géré.", 0),
        };
    }

    public function isPrincipale() : bool
    {
        return ($this->principale === 'O');
    }

    public function isHierarchique() : bool
    {
        return ($this->hierarchique === 'O');
    }

    public function isFonctionnelle() : bool
    {
        return ($this->fonctionnelle === 'O');
    }

    public function getQuotite() : ?int
    {
        return $this->quotite;
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