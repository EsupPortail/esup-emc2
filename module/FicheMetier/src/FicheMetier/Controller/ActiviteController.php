<?php

namespace FicheMetier\Controller;

use DateTime;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Form\Activite\ActiviteFormAwareTrait;
use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use FicheReferentiel\Form\Importation\ImportationFormAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;

class ActiviteController extends AbstractActionController
{
    use ActiviteServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ActiviteFormAwareTrait;
    use ImportationFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $activites = $this->getActiviteService()->getActivites(true);

        return new ViewModel([
           'activites' => $activites,
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
    public function supprimerAction(): ViewModel {

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

    public function importerAction(): ViewModel {

        $form = $this->getImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/importer', [ 'path' => null ], [], true));

        $activites = []; $info = []; $warning = []; $error = [];

        $request = $this->getRequest();
        if ($request->isPost()) {
            $continue = true; $referentiel = null;

            $data = $request->getPost();
            if (isset($data['referentiel'])) {
                $referentiel = $this->getReferentielService()->getReferentiel($data['referentiel']);
            }
            if ($referentiel === null) {
                $error[] = "Référentiel non trouvé";
                $continue = false;
            }
            $files = $request->getFiles()->toArray();
            $file = !empty($files)?current($files):null;

            if ($file['size'] === 0 AND $data['filepath'] === "") {
                    $error[] = "Fichier non trouvé";
                    $continue = false;
            } else {
                if ($file['size'] !== 0) {
                    $filepath = '/tmp/import_activites_' . (new DateTime())->getTimestamp() . '.csv';
                    copy($file['tmp_name'], $filepath);
                    $form->get('filepath')->setValue($filepath);
                    $data['filepath'] = $filepath;
                } else {
                    $file['tmp_name'] = $data['filepath'];
                    $form->get('filepath')->setValue($data['filepath']);
                }
            }

            if ($continue) {

                $result = $this->getActiviteService()->readFromCsv($referentiel, $file['tmp_name']);
                $activites = $result['activités']; $created = $result['created']; $updated = $result['updated'];
                $info = array_merge($info, $result['info']);
                $warning = array_merge($warning, $result['warning']);
                $error = array_merge($error, $result['error']);
                $info[] = count($activites) . " activité·s lue·s depuis le fichier CSV";


                if (isset($data['mode']) and $data['mode'] === 'import') {
                    $nbCreated = 0; $nbUpdated = 0;
                    foreach ($created as $activite) {
                        $this->getActiviteService()->create($activite);
                        $nbCreated++;
                    }
                    if ($nbCreated !== 0) $info[] = $nbCreated . " activité·s crée·s";
                    foreach ($updated as $activite) {
                            $this->getActiviteService()->update($activite);
                            $nbUpdated++;
                    }
                    if ($nbUpdated !== 0) $info[] = $nbUpdated . " activité·s mise·s à jour";
                }
            }
        }

        return new ViewModel([
            'data' => $data??null,
            'file' => $file??null,
            'form' => $form,
            'activites' => $activites,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,
        ]);
    }

    //todo fonction de recherche
    // public function rechercherAction(): Json {}


}
