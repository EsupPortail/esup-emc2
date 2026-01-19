<?php

namespace FicheMetier\Controller;

use DateTime;
use Exception;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Form\Activite\ActiviteFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use FicheMetier\Service\ActiviteElement\ActiviteElementServiceAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use RuntimeException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class ActiviteController extends AbstractActionController
{
    use ActiviteServiceAwareTrait;
    use ActiviteElementServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ActiviteFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $activites = $this->getActiviteService()->getActivitesWithFiltre($params);

        return new ViewModel([
            'activites' => $activites,
            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);

        return new ViewModel([
            'title' => "Affichage de l'activité",
            'activite' => $activite,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $activite = new Activite();
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/ajouter', [], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une activité",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    // todo blocage si utilisée
    public function modifierAction(): ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/modifier', ['activite' => $activite?->getId()], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'une activité",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $this->getActiviteService()->historise($activite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('activite', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $this->getActiviteService()->restore($activite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('activite', [], [], true);
    }

    // todo blocage si utilisée
    public function supprimerAction(): ViewModel
    {

        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getActiviteService()->delete($activite);
            exit();
        }

        $vm = new ViewModel();
        if ($activite !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'activité",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('activite/supprimer', ["activite" => $activite->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function importerAction(): ViewModel
    {

        $displayCodeFonction = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);

        $separateur = "|";
        $request = $this->getRequest();

        $data = null;
        $activites = [];
        $error = [];
        $warning = [];
        $info = [];

        $filename = null;
        $filepath = null;

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles()->toArray();
            $file = !empty($files) ? current($files) : null;

            $filename = $data['filename'] ?? null;
            $filepath = $data['filepath'] ?? null;

            if (($file === null or $file['tmp_name'] === "") and $filepath === null) {
                $error[] = "Aucun fichier fourni";
            } else {
                if ($filepath === null) {
                    $filepath = '/tmp/import_activite_' . (new DateTime())->getTimestamp() . '.csv';
                    $filename = $file['name'];
                    copy($file['tmp_name'], $filepath);
                }
            }
            if ($data['referentiel'] === '') {
                $error[] = "Aucun référentiel sélectionné";
            }

            if ($data['mode'] and $data['referentiel'] !== '' and $filepath) {
                $mode = $data['mode'];
                if (!in_array($mode, ['preview', 'import'])) {
                    $error[] = "Le mode sélectionné est non valide (" . $mode . " doit être soit 'preview' soit 'import')";
                }

                $array = $this->readCSV($filepath, true, ",");
                if (empty($array)) {
                    $warning[] = "Le fichier ne contient pas de données.";
                } else {

                    // Vérification des colonnes et référentiel ////////////////////////////////////////////////////////
                    $header = [];
                    foreach ($array[0] as $key => $value) {
                        $header[] = $key;
                    }
                    $hasId = in_array(Activite::ACTIVITE_HEADER_ID, $header);
                    if (!$hasId) $error[] = "La colonne obligatoire [" . Activite::ACTIVITE_HEADER_ID . "] est manquante";
                    $hasLibelle = in_array(Activite::ACTIVITE_HEADER_LIBELLE, $header);
                    if (!$hasLibelle) $error[] = "La colonne obligatoire [" . Activite::ACTIVITE_HEADER_LIBELLE . "] est manquante";
                    $hasDescription = in_array(Activite::ACTIVITE_HEADER_DESCRIPTION, $header);
                    if (!$hasDescription) $warning[] = "La colonne facultative [" . Activite::ACTIVITE_HEADER_DESCRIPTION . "] est manquante";
                    $hasCodesFichesMetiers = in_array(Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE, $header);
                    if (!$hasCodesFichesMetiers) $warning[] = "La colonne facultative [" . Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE . "] est manquante";
                    $hasCodesFonctions = in_array(Activite::ACTIVITE_HEADER_CODES_FONCTION, $header);
                    if (!$hasCodesFonctions and $displayCodeFonction) $warning[] = "La colonne facultative [" . Activite::ACTIVITE_HEADER_CODES_FONCTION . "] est manquante";

                    $referentiel = $this->getReferentielService()->getReferentiel($data['referentiel']);
                    if ($referentiel === null) $error[] = "Le référentiel n'a pas pu être récupéré.";


                    if ($hasId and $hasLibelle and $referentiel !== null) {
                        $position = 1;

                        // Lecture des données /////////////////////////////////////////////////////////////////////////////

                        foreach ($array as $row) {
                            $position++;
                            try {
                                $activite = $this->getActiviteService()->createOneWithCsv($row, $separateur, $referentiel, $position);
                                $activites[] = $activite;
                            } catch (Exception $e) {
                                throw new RuntimeException("Un problème est survenue lors de la lecture du fichier CSV", 0, $e);
                            }
                        }

                        //  Importation ////////////////////////////////////////////////////////////////////////////////
                        if ($mode === 'import') {

                            foreach ($activites as $activite) {

                                // Entity Management ///////////////////////////////////////////////////////////////////
                                if ($activite->getId() === null) {
                                    $this->getActiviteService()->create($activite);
                                } else {
                                    $this->getActiviteService()->update($activite);
                                }


                                // TODO Factoriser  ////////////////////////////////////////////////////////////////////
                                // Gestion des codes emploi type ///////////////////////////////////////////////////////
                                $codesFicheMetier = explode($separateur, $activite->getCodesFicheMetier() ?? "");
                                $codesFicheMetier = array_map('trim', $codesFicheMetier);
                                $codesFicheMetier = array_filter($codesFicheMetier, function (string $a) {
                                    return $a !== '';
                                });
                                foreach ($codesFicheMetier as $codeFicheMetier) {
                                    $fichemetier = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $codeFicheMetier);
                                    if ($fichemetier === null) {
                                        $warning[] = "La fiche metier <span class='badge' style='background:".$referentiel->getCouleur().">" . $referentiel->getLibelleCourt() . " - " .$activite->getCodesFicheMetier() . "</span> n&apos;existe pas";
                                    } else {
                                        if (!$fichemetier->hasActivite($activite)) {
                                            $this->getActiviteElementService()->addActiviteElement($fichemetier, $activite);
                                            $info[] = "Ajout de l'activité " . $activite->printReference() . " à la fiche metier [" . ($fichemetier->getReference() ?? ("Fiche #" . $fichemetier->getId())) . "]";
                                        }
                                    }
                                }

                                // TODO Factoriser  ////////////////////////////////////////////////////////////////////
                                // Gestion des codes fonction //////////////////////////////////////////////////////////
                                $codesFonction = explode('|', $activite->getCodesFonction() ?? "");
                                $codesFonction = array_map('trim', $codesFonction);
                                $codesFonction = array_filter($codesFonction, function (string $a) {
                                    return $a !== '';
                                });
                                foreach ($codesFonction as $codeFonction) {
                                    $codeFonction_ = $this->getCodeFonctionService()->getCodeFonctionByCode($codeFonction);
                                    if ($codeFonction_ === null) {
                                        $warning[] = "Le code fonction <code>" . $codeFonction . "</code> n’existe pas ; la mission principale " . $activite->printReference() . " ne sera ajoutée à aucune fiche métier.";
                                    } else {
                                        $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersByCodeFonction($codeFonction);
                                        if (empty($fichesmetiers)) {
                                            $warning[] = "Aucune fiche métier utilise le code fonction <code>" . $codeFonction . "</code> ; l'activité ne sera ajoutée à aucune fiche métier.";
                                        }
                                        foreach ($fichesmetiers as $fichemetier) {
                                            if (!$fichemetier->hasActivite($activite)) {
                                                $this->getActiviteElementService()->addActiviteElement($fichemetier, $activite);
                                                $info[] = "Ajout de la mission [" . $activite->getReference() . "] à la fiche metier [" . ($fichemetier->getReference() ?? ("Fiche #" . $fichemetier->getId())) . "]";
                                            }
                                        }
                                    }
                                }
                                //traitement des codes ...
                            }

                        }
                    }
                }
            }
        }

        return new ViewModel([
            'separateur' => $separateur,
            'displayCodeFonction' => $displayCodeFonction,

            'activites' => $activites,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,

            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'data' => $data,
            'filepath' => $filepath,
            'filename' => $filename,
        ]);



    }


    public function readCSV(string $fichier_path, bool $explodeMultiline = false, string $separator = '|'): array
    {
        $handle = fopen($fichier_path, "r");

        $header = fgetcsv($handle, null, ";");
        // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
        $header[0] = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header[0]);
        $header = array_map('trim', $header);

        $array = [];
        while ($content = fgetcsv($handle, 0, ";")) {
            $all = implode($separator, $content);
            $encoding = mb_detect_encoding($all, 'UTF-8, ISO-8859-1');
            $content = array_map(function (string $st) use ($encoding) {
                $st = str_replace(chr(63), '\'', $st);
                $st = mb_convert_encoding($st, 'UTF-8', $encoding);
                return $st;
            }, $content);
            $array[] = $content;
        }

        $jsonData = [];
        foreach ($array as $item) {
            $jsonItem = [];
            for ($position = 0; $position < count($header); $position++) {
                $key = $header[$position];
                if ($explodeMultiline) {
                    if (strstr($item[$position], PHP_EOL)) {
                        $jsonItem[$key] = explode(PHP_EOL, $item[$position]);
                    } else {
                        $jsonItem[$key] = $item[$position];
                    }
                } else {
                    $jsonItem[$key] = $item[$position];
                }
            }
            $jsonData[] = $jsonItem;
        }

        return $jsonData;
    }



}
