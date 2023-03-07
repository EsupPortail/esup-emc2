<?php

namespace Referentiel\Service\Synchronisation;

use DateTime;
use Referentiel\Service\SqlHelper\SqlHelperServiceAwareTrait;

class SynchronisationService {
    use SqlHelperServiceAwareTrait;

    private array $entityManagers = [];
    public function setEntityManagers(array $entityManagers): void
    {
        $this->entityManagers = $entityManagers;
    }

    private array $configs = [];
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }
    public function getFromConfig(string $name, string $key)
    {
        return $this->configs[$name][$key];
    }

    private function checkDifferences(array $itemSource, array $itemDestination, array $correspondance) : bool {
        foreach ($correspondance as $idSource => $idCorrespondance) {
            if ($itemSource[$idSource] != $itemDestination[$idCorrespondance]) {
//                var_dump($itemSource[$idSource]);
//                var_dump($itemDestination[$idCorrespondance]);
//                var_dump("Diff: " . $idSource . " >>> " . $itemSource[$idSource] . "!=" . $itemDestination[$idCorrespondance]);
                return true;
            }
        }
        return false;
    }

    public function synchronise(string $name) : string
    {
        $log = "Synchronisation [".$name."]\n";
        $debut = new DateTime();
        $log .= "Debut: ".$debut->format('d/m/y H:i:s:u')."\n";

        $correspondance = $this->getFromConfig($name, 'correspondance');
        $orm_source = $this->entityManagers[$this->getFromConfig($name, 'orm_source')];
        $orm_destination = $this->entityManagers[$this->getFromConfig($name, 'orm_destination')];
        $table_source = $this->getFromConfig($name, 'table_source');
        $table_destination = $this->getFromConfig($name, 'table_destination');
        $id_source = $this->getFromConfig($name, 'id');
        $id_destination = $correspondance[$id_source];

        $data_source        = $this->getSqlHelperService()->fetch($orm_source, $table_source, $correspondance, 'source', $id_source);
        $data_destination   = $this->getSqlHelperService()->fetch($orm_destination, $table_destination, $correspondance, 'destination', $id_destination);

//        var_dump($data_source);
//        var_dump($data_destination);

        //check for removal
        $nbRetrait = 0; $texte_retrait = "";
        foreach ($data_destination as $id => $item) {
            if ($item['deleted_on'] === null AND !isset($data_source[$id])) {
                $nbRetrait++; $texte_retrait .= "Retrait de ".$id." des données destination.\n";
                $this->getSqlHelperService()->delete($orm_destination, $table_destination, $id);
            }
        }
//        var_dump($nbRetrait);
//        var_dump($texte_retrait);
        $log .= "#Retrait: ".$nbRetrait."\n";
        $log .= $texte_retrait;

        //check for adding
        $nbAjout = 0; $texte_ajout = "";
        foreach ($data_source as $id => $item) {
            if (!isset($data_destination[$id])) {
                $nbAjout++; $texte_ajout .= "Ajout de ".$id." des données sources.\n";
                $this->getSqlHelperService()->insert($orm_destination, $table_destination, $item, $correspondance);
            }
        }
//        var_dump($nbAjout);
//        var_dump($texte_ajout);
        $log .= "#Ajout: ".$nbAjout."\n";
        $log .= $texte_ajout;

        //check for restauration
        $nbRestauration = 0; $texte_restauration = "";
        foreach ($data_source as $id => $item) {
            if (isset($data_destination[$id]) AND $data_destination[$id]["deleted_on"] !== null) {
                $nbRestauration++; $texte_restauration .= "Restauration de ".$id." des données destinations.\n";
                $this->getSqlHelperService()->restore($orm_destination, $table_destination, $id);
            }
        }
//        var_dump($nbRestauration);
//        var_dump($texte_restauration);
        $log .= "#Restauration: ".$nbRestauration."\n";
        $log .= $texte_restauration;

        //check for modification
        $nbModification = 0; $texte_modication = "";
        foreach ($data_source as $id => $item) {
            if (isset($data_destination[$id]) AND $this->checkDifferences($item, $data_destination[$id], $correspondance)) {
                $nbModification++; $texte_modication .= "Modif de ".$id." des données sources.\n";
                $this->getSqlHelperService()->update($orm_destination, $table_destination, $item, $correspondance, $id);
            }
        }
//        var_dump($nbModification);
//        var_dump($texte_modication);
        $log .= "#Modification: ".$nbModification."\n";
        $log .= $texte_modication;

        $fin = new DateTime();
        $log .= "Fin: ".$fin->format('d/m/y H:i:s:u')."\n";

        $log .= "Durée de la synchronisation: " . ($fin->diff($debut))->format('%H:%m:%s:%F');

        return $log;
    }
}