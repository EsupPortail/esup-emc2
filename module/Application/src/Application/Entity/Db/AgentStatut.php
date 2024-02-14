<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use RuntimeException;
use Structure\Entity\Db\Structure;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentStatut implements HasPeriodeInterface {
    use HasPeriodeTrait;
    use DbImportableAwareTrait;

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
    private ?string $langueMaladie = 'N';

    public function getId() : ?string
    {
        return $this->id;
    }

    /**
     * @return Agent|null
     */
    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    /**
     * @return Structure
     */
    public function getStructure() : ?Structure
    {
        return $this->structure;
    }

    /** Gestion des témoins ***************************************************************************************/

    const TEMOINS = [
        'cdi', 'cdd', 'titulaire', 'vacataire',
        'enseignant', 'administratif', 'chercheur', 'doctorant',
        'detacheIn', 'detacheOut','dispo', 'longue_maladie', 'conge_parental'
    ];

    public function getTemoin(string $temoin) : bool
    {
        switch ($temoin) {
            case 'cdi' : return $this->isCdi();
            case 'cdd' : return $this->isCdd();
            case 'titulaire' : return $this->isTitulaire();
            case 'vacataire' : return $this->isVacataire();
            case 'enseignant' : return $this->isEnseignant();
            case 'administratif' : return $this->isAdministratif();
            case 'chercheur' : return $this->isChercheur();
            case 'doctorant' : return $this->isDoctorant();
            case 'detacheIn' : return $this->isDetacheIn();
            case 'detacheOut' : return $this->isDetacheOut();
            case 'dispo' : return $this->isDispo();
            case 'longue_maladie' : return $this->isLangueMaladie();
            case 'conge_parental' : return $this->isCongeParental();
            default :
                throw new RuntimeException("Le temoin [" . $temoin . "] est inconnu ou non géré.", 0);
        }
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

    public function isLangueMaladie(): bool
    {
        return $this->langueMaladie === 'O';
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