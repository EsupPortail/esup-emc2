<?php

namespace FicheMetier\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class ImportController extends AbstractActionController
{
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    public function importAction(): ViewModel
    {
        $path = './upload/REFERENS3.csv';

        $csvFile = fopen($path, "r"); // Ouvrir le fichier en mode lecture
        $debug = true;
        $mode = "preview";
        $info = [];
        $warning = [];
        $error = [];

        if ($csvFile !== false) {
            //lecture du header
            $header = fgetcsv($csvFile, null, ";");

            $famillePosition = -1; $familleDictionnary = [];
            $domainePosition = -1; $domaineDictionnary = [];
            foreach ($header as $key => $value) {
//            var_dump($key); var_dump($value);
                if ($value === "Famille d’activité professionnelle") $famillePosition = $key;
                if ($value === "Domaine de formation souhaité/exigé") $domainePosition = $key;
            }
            if ($famillePosition === -1) $warning[] = "Échec de la détection de la colonne [Famille d’activité professionnelle]";
            if ($domainePosition === -1) $warning[] = "Échec de la détection de la colonne [Domaine de formation souhaité/exigé]";

            while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                if ($famillePosition !== -1) {
                    $famille_ = $row[$famillePosition];
                    $familles = explode("|", $famille_);
                    foreach ($familles as $famille) $familleDictionnary[$famille] = $famille;
                }
                if ($domainePosition !== -1) {
                    $domaine_ = $row[$domainePosition];
                    $domaines = explode("|", $domaine_);
                    foreach ($domaines as $domaine) $domaineDictionnary[$domaine] = $domaine;
                }
            }
            fclose($csvFile); // Fermer le fichier

            if ($debug) {
                print("Famille");
                sort($familleDictionnary);
                var_dump($familleDictionnary);
                print("Domaine");
                sort($domaineDictionnary);
                var_dump($domaineDictionnary);
            }

            foreach ($familleDictionnary as $famille) {
                $res = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle($famille);
                if ($res === null) {
                    $info[] = "Création de la famille professionnelle [".$famille."]";
                    if ($mode === 'import') $this->getFamilleProfessionnelleService()->createWith($famille);
                }
            }

        } else {
            echo "Erreur lors de l'ouverture du fichier.";
        }

        print("INFO");
        var_dump($info);
        print("WARNING");
        var_dump($warning);
        print("ERROR");
        var_dump($error);
        die();
        fclose($csvFile);

    }

}