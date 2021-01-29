<?php

namespace UnicaenUtilisateur\Entity;

use DateTime;
use Exception;
use UnicaenApp\Exception\RuntimeException;

trait DateTimeAwareTrait {

    /**
     * @return DateTime
     */
    public function getDateTime()
    {
        try {
            $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date",0, $e);
        }
        return $date;
    }

    public function getAnneeScolaire()
    {
        $date = $this->getDateTime();
        $mois = intval($date->format('m'));
        $annee = intval($date->format('Y'));

        if ($mois >= 9) {
            $anneeScolaire = $annee . "/" . ($annee+1);
        } else {
            $anneeScolaire = ($annee-1) . "/" . $annee;
        }
        return $anneeScolaire;
    }

    /**
     * @param int $anneeDebut
     * @param int $anneeFin
     * @return array
     */
    public function getAnneesScolaires(int $anneeDebut, int $anneeFin) {
        $result = [];
        for ($annee = $anneeDebut ; $annee <= $anneeFin; $annee++) {
            $string = $annee ."/". ($annee+1);
            $result[$string] = $string;
        }
        return $result;
    }
}