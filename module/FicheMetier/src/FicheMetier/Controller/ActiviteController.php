<?php

namespace FicheMetier\Controller;

use DateTime;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Form\Activite\ActiviteFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Provider\Privilege\ActivitePrivileges;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use FicheMetier\Service\ActiviteElement\ActiviteElementServiceAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Referentiel\Provider\Privilege\ReferentielPrivileges;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ActiviteController extends AbstractActionController
{
    use ActiviteServiceAwareTrait;
    use ActiviteElementServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use UserServiceAwareTrait;
    use ActiviteFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $activites = $this->getActiviteService()->getActivitesWithFiltre($params);
        $dictionnaire = $this->getActiviteService()->generateDictionnaireFicheMetier();

        return new ViewModel([
            'activites' => $activites,
            'dictionnaire' => $dictionnaire,
            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $activite = $this->getActiviteService()->getRequestedActivite($this);
        $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersHavingActivite($activite);

        return new ViewModel([
            'title' => "Affichage de l'activité",
            'activite' => $activite,
            'fichesmetiers' => $fichesmetiers,
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
        $vm->setTemplate('fiche-metier/activite/modifier');
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
        $vm->setTemplate('fiche-metier/activite/modifier');
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

        $colonnesObligatoires = [Activite::ACTIVITE_HEADER_ID, Activite::ACTIVITE_HEADER_LIBELLE];

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


                $csvFile = fopen($filepath, "r");
                // lecture du header + position colonne
                if ($csvFile !== false) {
                    $header = fgetcsv($csvFile, null, ";");
                    // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
                    $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
                    $nbElements = count($header);

                    foreach ($colonnesObligatoires as $colonne) {
                        if (!in_array($colonne, $header)) $error[] = "La colonne obligatoire <strong>" . $colonne . "</strong> est absente.";
                    }

                    $referentiel = $this->getReferentielService()->getReferentiel($data['referentiel']);
                    if ($referentiel === null) $error[] = "Le référentiel n'a pas pu être récupéré.";


                    $raws = [];
                    while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                        $item = [];
                        for ($position = 0; $position < $nbElements; ++$position) {
                            $item[$header[$position]] = $row[$position];
                        }
                        $raws[] = $item;
                    }

                    if (empty($error)) {
                        $ligne = 1;
                        foreach ($raws as $raw) {
                            $ligne++;

                            /** Check des valeurs obligatoires **/
                            $allGood = true;
                            foreach ($colonnesObligatoires as $colonne) {
                                if (trim($raw[$colonne]) === "") {
                                    $error[] = "La colonne obligatoire <strong>" . $colonne . "</strong> n'a pas de valeur la ligne " . $ligne . " a été ignorée";
                                    $allGood = false;
                                }
                            }

                            if ($allGood) {
                                $idOrig = trim($raw[Activite::ACTIVITE_HEADER_ID]);
                                $libelle = trim($raw[Activite::ACTIVITE_HEADER_LIBELLE]);

                                $activite = $this->getActiviteService()->getActiviteByReference($referentiel, $idOrig);
                                if ($activite === null) {
                                    $activite = new Activite();
                                    $activite->setReferentiel($referentiel);
                                    $activite->setReference($idOrig);
                                }
                                $activite->setLibelle($libelle);

                                if (isset($raw[Activite::ACTIVITE_HEADER_DESCRIPTION])) {
                                    $description = $raw[Activite::ACTIVITE_HEADER_DESCRIPTION] ?? trim($raw[Activite::ACTIVITE_HEADER_DESCRIPTION]);
                                    $activite->setDescription($description !== '' ? $description : null);
                                }
                                if (isset($raw[Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE])) {
                                    $codeEmploiType = $raw[Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE] ?? trim($raw[Activite::ACTIVITE_HEADER_CODES_EMPLOITYPE]);
                                    $activite->setCodesFicheMetier($codeEmploiType !== '' ? $codeEmploiType : null);
                                }
                                if (isset($raw[Activite::ACTIVITE_HEADER_CODES_FONCTION])) {
                                    $codeFonction = $raw[Activite::ACTIVITE_HEADER_CODES_FONCTION] ?? trim($raw[Activite::ACTIVITE_HEADER_CODES_FONCTION]);
                                    $activite->setCodesFonction($codeFonction !== '' ? $codeFonction : null);
                                }
                                $activite->setRaw(json_encode($raw));

                                $activites[] = $activite;
                            }
                        }

                        if ($mode === 'import') {

                            $info[] = "Importation terminée.";
                            $role = $this->getUserService()->getConnectedRole();
                            $allowedActivite = $this->getPrivilegeService()->checkPrivilege(ActivitePrivileges::ACTIVITE_AFFICHER, $role);
                            $allowedFicheMetier = $this->getPrivilegeService()->checkPrivilege(FicheMetierPrivileges::FICHEMETIER_AFFICHER, $role);
                            $allowedReferentiel = $this->getPrivilegeService()->checkPrivilege(ReferentielPrivileges::REFERENTIEL_AFFICHER, $role);

                            foreach ($activites as $activite) {

                                // Entity Management ///////////////////////////////////////////////////////////////////
                                if ($activite->getId() === null) {
                                    $this->getActiviteService()->create($activite);
                                } else {
                                    $this->getActiviteService()->update($activite);
                                }

                                // Gestion des codes emploi type ///////////////////////////////////////////////////////
                                $codesFicheMetier = explode($separateur, $activite->getCodesFicheMetier() ?? "");
                                $codesFicheMetier = array_map('trim', $codesFicheMetier);
                                $codesFicheMetier = array_filter($codesFicheMetier, function (string $a) {
                                    return $a !== '';
                                });
                                foreach ($codesFicheMetier as $codeFicheMetier) {
                                    $fichemetier = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $codeFicheMetier);
                                    if ($fichemetier === null) {
                                        $message = "Aucune fiche métier identifiée " . $codeFicheMetier . " dans le référentiel ";
                                        $message .= $referentiel ? $referentiel->printReference("lien", $this->url()->fromRoute("referentiel", [], [], true), $allowedReferentiel) : "<span class='badge' style='background: grey;'>Aucun référentiel</span>";
                                        $message .= ". ";
                                        $message .= "L'activité \"" . $activite->getLibelle() . "\" ";
                                        $message .= $activite->printReference("modal", $this->url()->fromRoute('activite/afficher', ['activite' => $activite->getId()], [], true), $allowedActivite);
                                        $message .= " ne sera pas ajoutée.";
                                        $warning[] = $message;
                                    } else {
                                        if (!$fichemetier->hasActivite($activite)) {
                                            $this->getActiviteElementService()->addActiviteElement($fichemetier, $activite);
                                            $message = "L'activité \"" . $activite->getLibelle() . "\" ";
                                            $message .= $activite->printReference("modal", $this->url()->fromRoute('activite/afficher', ['activite' => $activite->getId()], [], true), $allowedActivite);
                                            $message .= " a été ajoutée à la fiche métier \"" . $fichemetier->getLibelle() . "\" ";
                                            $message .= $fichemetier->printReference("lien", $this->url()->fromRoute('fiche-metier/afficher', ['fiche-metier' => $fichemetier->getId()], [], true), $allowedFicheMetier);
                                            $info[] = $message;
                                        }
                                    }
                                }

                                // Gestion des codes fonction //////////////////////////////////////////////////////////
                                $codesFonction = explode('|', $activite->getCodesFonction() ?? "");
                                $codesFonction = array_map('trim', $codesFonction);
                                $codesFonction = array_filter($codesFonction, function (string $a) {
                                    return $a !== '';
                                });
                                foreach ($codesFonction as $codeFonction) {
                                    $codeFonction_ = $this->getCodeFonctionService()->getCodeFonctionByCode($codeFonction);
                                    if ($codeFonction_ === null) {
                                        $message = "Le code fonction <code>" . $codeFonction . "</code> n’existe pas. ";
                                        $message .= "L'activité \"" . $activite->getLibelle() . "\" ";
                                        $message .= $activite->printReference("modal", $this->url()->fromRoute('activite/afficher', ['activite' => $activite->getId()], [], true), $allowedActivite);
                                        $message .= " ne peut pas être ajoutée.";
                                        $warning[] = $message;
                                    } else {
                                        $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersByCodeFonction($codeFonction);
                                        if (empty($fichesmetiers)) {
                                            $warning[] = "Aucune fiche métier utilise le code fonction <code>" . $codeFonction . "</code> ; l'activité ne sera ajoutée à aucune fiche métier.";
                                        }
                                        foreach ($fichesmetiers as $fichemetier) {
                                            if (!$fichemetier->hasActivite($activite)) {
                                                $this->getActiviteElementService()->addActiviteElement($fichemetier, $activite);
                                                $message = "L'activité \"" . $activite->getLibelle() . "\" ";
                                                $message .= $activite->printReference("modal", $this->url()->fromRoute('activite/afficher', ['activite' => $activite->getId()], [], true), $allowedActivite);
                                                $message .= "a été ajoutée à la fiche métier \"" . $fichemetier->getLibelle() . "\" " . $fichemetier->printReference();
                                                $info[] = $message;
                                            }
                                        }
                                    }
                                }
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

}
