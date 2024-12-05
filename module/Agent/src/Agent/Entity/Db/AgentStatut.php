<?php

namespace Agent\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use RuntimeException;
use Structure\Entity\Db\Structure;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;

class AgentStatut implements HasPeriodeInterface, IsSynchronisableInterface {
    use HasPeriodeTrait;
    use IsSynchronisableTrait;

    private ?string $id = null;
    private ?Agent $agent = null;
    private ?Structure $structure = null;

    private ?string $titulaire = 'N';
    private ?string $cdi = 'N';
    private ?string $cdd = 'N';
    private ?string $vacataire = 'N';
    private ?string $enseignant = 'N';
    private ?string $administratif = 'N';
    private ?string $chercheur = 'N';
    private ?string $etudiant = 'N';
    private ?string $auditeurLibre = 'N';
    private ?string $doctorant = 'N';
    private ?string $detacheIn = 'N';
    private ?string $detacheOut = 'N';
    private ?string $dispo = 'N';
    private ?string $heberge = 'N';
    private ?string $emerite = 'N';
    private ?string $retraite = 'N';
    private ?string $congeParental = 'N';
    private ?string $longueMaladie = 'N';


    /** Données : cette donnée est synchronisée >> par conséquent, il n'y a que des getters */

    public function getId() : ?string
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

    const TEMOINS = [
        'cdi', 'cdd', 'titulaire', 'vacataire',
        'enseignant', 'administratif', 'chercheur', 'doctorant',
        'detacheIn', 'detacheOut','dispo', 'longue_maladie', 'conge_parental'
    ];

    public function getTemoin(string $temoin) : bool
    {
        return match ($temoin) {
            'cdi' => $this->isCdi(),
            'cdd' => $this->isCdd(),
            'titulaire' => $this->isTitulaire(),
            'vacataire' => $this->isVacataire(),
            'enseignant' => $this->isEnseignant(),
            'administratif' => $this->isAdministratif(),
            'chercheur' => $this->isChercheur(),
            'doctorant' => $this->isDoctorant(),
            'detacheIn' => $this->isDetacheIn(),
            'detacheOut' => $this->isDetacheOut(),
            'dispo' => $this->isDispo(),
            'longue_maladie' => $this->isLongueMaladie(),
            'conge_parental' => $this->isCongeParental(),
            default => throw new RuntimeException("Le temoin [" . $temoin . "] est inconnu ou non géré.", 0),
        };
    }
    
    public function isTitulaire() : bool
    {
        return $this->titulaire === 'O';
    }

    public function isCdi() : bool
    {
        return $this->cdi === 'O';
    }

    public function isCdd() : bool
    {
        return $this->cdd === 'O';
    }

    public function isVacataire() : bool
    {
        return $this->vacataire === 'O';
    }

    public function isEnseignant() : bool
    {
        return $this->enseignant === 'O';
    }

    public function isAdministratif() : bool
    {
        return $this->administratif === 'O';
    }

    public function isChercheur() : bool
    {
        return $this->chercheur === 'O';
    }

    public function isEtudiant() : bool
    {
        return $this->etudiant === 'O';
    }

    public function isAuditeurLibre() : bool
    {
        return $this->auditeurLibre === 'O';
    }

    public function isDoctorant() : bool
    {
        return $this->doctorant === 'O';
    }

    public function isDetacheIn() : bool
    {
        return $this->detacheIn === 'O';
    }

    public function isDetacheOut() : bool
    {
        return $this->detacheOut === 'O';
    }

    public function isDispo() : bool
    {
        return $this->dispo === 'O';
    }

    public function isHeberge() : bool
    {
        return $this->heberge === 'O';
    }

    public function isEmerite() : bool
    {
        return $this->emerite === 'O';
    }

    public function isRetraite() : bool
    {
        return $this->retraite === 'O';
    }

    public function isCongeParental(): bool
    {
        return $this->congeParental === 'O';
    }

    public function isLongueMaladie(): bool
    {
        return $this->longueMaladie === 'O';
    }

    /** Fonction pour affichage/macro *********************************************************************************/

    /**
     * @param AgentStatut[] $agentStatuts
     * @return string[]
     */
    public static function generateStatutsArray(array $agentStatuts) : array
    {
        $statuts = [];
        foreach ($agentStatuts as $agentStatut) {
            if ($agentStatut->isTitulaire()) $statuts['Titulaire'] = 'Titulaire';
            if ($agentStatut->isCdi()) $statuts['C.D.I'] = 'C.D.I.';
            if ($agentStatut->isCdd()) $statuts['C.D.D'] = 'C.D.D.';
            if ($agentStatut->isAdministratif()) $statuts['Administratif'] = 'Administratif';
        }
        return $statuts;
    }
}