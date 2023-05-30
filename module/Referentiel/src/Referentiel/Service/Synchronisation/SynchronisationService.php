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

    public array $configs = [];
    public function setConfigs(array $configs): void
    {
        $this->configs = $configs;
    }
    public function getFromConfig(string $name, string $key)
    {
        return $this->configs[$name][$key];
    }

    private function checkDifferences(array $itemSource, array $itemDestination, array $correspondance, ?string $source = null) : bool {
        foreach ($correspondance as $idSource => $idCorrespondance) {
            if ($itemSource[$idSource] != $itemDestination[$idCorrespondance]) {
//                var_dump($itemSource[$idSource]);
//                var_dump($itemDestination[$idCorrespondance]);
//                var_dump("Diff: " . $idSource . " >>> " . $itemSource[$idSource] . "!=" . $itemDestination[$idCorrespondance]);
//                die();
                return true;
            }
        }
        if ($source !== null) {
//            var_dump($source);
//            var_dump($itemDestination);
//            die();
//            var_dump($itemDestination['source_id']);
//            die();
            if ($itemDestination['source_id'] !== $source) return true;
        }
        return false;
    }

    public function synchronise(string $name) : string
    {
        $log = "";
        $line =  "Synchronisation [".$name."]\n";
        echo $line; flush(); $log .= "<strong>" . $line ."</strong><br>";

        $debut = new DateTime();

        $line = "Debut: ".$debut->format('d/m/y H:i:s:u')."\n"; flush();
        echo $line; flush(); $log .= $line ."<br>";

        $correspondance = $this->getFromConfig($name, 'correspondance');
        $orm_source = $this->entityManagers[$this->getFromConfig($name, 'orm_source')];
        $orm_destination = $this->entityManagers[$this->getFromConfig($name, 'orm_destination')];
        $table_source = $this->getFromConfig($name, 'table_source');
        $table_destination = $this->getFromConfig($name, 'table_destination');
        $id_source = $this->getFromConfig($name, 'id');
        $source = $this->getFromConfig($name, 'source');
        $id_destination = $correspondance[$id_source];

        $data_source        = $this->getSqlHelperService()->fetch($orm_source, $table_source, $correspondance, 'source', $id_source);

        $line =  count($data_source). " entrées dans les données sources.\n";
        echo $line; flush(); $log .= $line ."<br>";

        $data_destination   = $this->getSqlHelperService()->fetch($orm_destination, $table_destination, $correspondance, 'destination', $id_destination);
        $data_destination_on = []; $data_destination_off = [];
        foreach ($data_destination as $item) {
            if ($item['deleted_on'] !== null) $data_destination_off[] = $item; else $data_destination_on[] = $item;
        }

        $line =  count($data_destination_on) . "(~". count($data_destination_off).")". " entrées dans les données cibles actives.\n";
        echo $line; flush(); $log .= $line ."<br>";

        $read = new DateTime();

        $line = "Lecture: ".$read->format('d/m/y H:i:s:u'). "(" . ($read->diff($debut))->format('%H:%m:%s:%F').")\n";
        echo $line; flush(); $log .= $line ."<br>";

        //check for removal
        $nbRetrait = 0;
        foreach ($data_destination as $id => $item) {
            if ($item['deleted_on'] === null AND !isset($data_source[$id])) {
                $nbRetrait++;
                $this->getSqlHelperService()->delete($orm_destination, $table_destination, $id);
            }
        }

        $line = "#Retrait: ".$nbRetrait."\n";
        echo $line; flush(); $log .= $line ."<br>";

        //check for adding
        $nbAjout = 0;
        foreach ($data_source as $id => $item) {
            if (!isset($data_destination[$id])) {
                $nbAjout++;
                $this->getSqlHelperService()->insert($orm_destination, $table_destination, $item, $correspondance, $source);
            }
        }

        $line = "#Ajout: ".$nbAjout."\n";
        echo $line; flush(); $log .= $line ."<br>";

        //check for restauration
        $nbRestauration = 0;
        foreach ($data_source as $id => $item) {
            if (isset($data_destination[$id]) AND $data_destination[$id]["deleted_on"] !== null) {
                $nbRestauration++;
                $this->getSqlHelperService()->restore($orm_destination, $table_destination, $id);
            }
        }
        $line = "#Restauration: ".$nbRestauration."\n";
        echo $line; flush(); $log .= $line ."<br>";

        //check for modification
        $nbModification = 0;
        foreach ($data_source as $id => $item) {
            if (isset($data_destination[$id]) AND $this->checkDifferences($item, $data_destination[$id], $correspondance, $source)) {
                $nbModification++;
                $this->getSqlHelperService()->update($orm_destination, $table_destination, $item, $correspondance, $id, $source);
            }
        }
        $line .= "#Modification: ".$nbModification."\n";
        echo $line; flush(); $log .= $line ."<br>";

        $fin = new DateTime();

        $line = "Fin: ".$fin->format('d/m/y H:i:s:u')."\n";
        echo $line; flush(); $log .= $line ."<br>";

        $line =  "Durée de la synchronisation: " . ($fin->diff($debut))->format('%H:%m:%s:%F') . "\n";
        echo $line; flush(); $log .= $line ."<br>";

        return $log;
    }
}