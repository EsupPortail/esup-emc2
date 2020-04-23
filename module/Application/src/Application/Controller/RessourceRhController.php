<?php

namespace Application\Controller;

use Application\Service\Metier\MetierServiceAwareTrait;
use DateTime;
use UnicaenApp\View\Model\CsvModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    /** Trait utilisés pour les services */
    use MetierServiceAwareTrait;

    public function indexAction()
    {
        return new ViewModel([]);
    }

    /** CARTOGRAPHIE ***************************************************************************************************/

    public function cartographieAction() {
        $metiers = $this->getMetierService()->getMetiers();

        $results = [];
        foreach($metiers as $metier) {
            $fonction = $metier->getFonction();
            $domaine =  $metier->getDomaine();
            $famille = ($domaine)?$domaine->getFamille():null;
            $entry = [
                'famille'  => ($famille)?$famille->__toString():"---",
                'fonction' => ($fonction)?$fonction:"---",
                'domaine'  => ($domaine)?$domaine->__toString():"---",
                'metier'   => ($metier)?$metier->__toString():"---",
                'emploi-type'   => ($metier->getEmploiType())?$metier->getEmploiType():"---",
                'nbFiche'   => count($metier->getFichesMetiers()),
            ];
            $results[] = $entry;
        }

        usort($results, function($a, $b) {
            if ($a['famille'] !== $b['famille'])     return $a['famille'] < $b['famille'];
            if ($a['fonction'] !== $b['fonction'])   return $a['fonction'] < $b['fonction'];
            if ($a['domaine'] !== $b['domaine'])     return $a['domaine'] < $b['domaine'];
            return $a['metier'] < $b['metier'];
        });

        return new ViewModel([
            'results' => $results,
        ]);
    }

    public function exportCartographieAction() {
        $metiers = $this->getMetierService()->getMetiers();

        $results = [];
        foreach($metiers as $metier) {
            $fonction = $metier->getFonction();
            $domaine = ($metier)?$metier->getDomaine():null;
            $famille = ($domaine)?$domaine->getFamille():null;
            $entry = [
                'famille'  => ($famille)?$famille->__toString():"---",
                'domaine'  => ($domaine)?$domaine->__toString():"---",
                'fonction' => ($fonction)?:"---",
                'metier'   => ($metier)?$metier->__toString():"---",
                'emploi-type'   => ($metier->getEmploiType())?$metier->getEmploiType():"---",
                'nbFiche'   => count($metier->getFichesMetiers()),
            ];
            $results[] = $entry;
        }

        usort($results, function($a, $b) {
            if ($a['famille'] !== $b['famille'])     return $a['famille'] < $b['famille'];
            if ($a['domaine'] !== $b['domaine'])     return $a['domaine'] < $b['domaine'];
            if ($a['fonction'] !== $b['fonction'])   return $a['fonction'] < $b['fonction'];
            return $a['metier'] < $b['metier'];
        });

        $headers = ['Famille', 'Domaine', 'Fonction', 'Metier', 'Emploi-type', '#Fiche'];

        $today = new DateTime();

        $result = new CsvModel();
        $result->setDelimiter(';');
        $result->setEnclosure('"');
        $result->setHeader($headers);
        $result->setData($results);
        $result->setFilename('cartographie_metier_'.$today->format('Ymd-His').'.csv');

        return $result;
    }



}