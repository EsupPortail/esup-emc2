<?php

namespace FicheMetier\Controller;

use Application\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use Application\Provider\Etat\FicheMetierEtats;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Form\Raison\RaisonFormAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;

/** @method FlashMessenger flashMessenger() */

class FicheMetierController extends AbstractActionController {
    use ActiviteServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MetierServiceAwareTrait;

    use FicheMetierImportationFormAwareTrait;
    use RaisonFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SelectionnerMetierFormAwareTrait;

    public function indexAction() : ViewModel
    {
        return new ViewModel();
    }

    /** CRUD **********************************************************************************************************/

    public function afficherAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $missions = $this->getActiviteService()->getActivitesByFicheMetierType($fichemetier);
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);

        return new ViewModel([
            'fiche' => $fichemetier,
            'missions' => $missions,
            'competences' => $competences,
            'applications' => $applications,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $fiche = new FicheMetier();

        $form = $this->getSelectionnerMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/ajouter', [], [], true));
        $form->bind($fiche);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->create($fiche);
                $this->getFicheMetierService()->setDefaultValues($fiche);
                $this->getFicheMetierService()->update($fiche);

                $libelle = $this->getMetierService()->computeEcritureInclusive($fiche->getMetier()->getLibelleFeminin(), $fiche->getMetier()->getLibelleMasculin());
                $this->flashMessenger()->addSuccessMessage(
                    "Une nouvelle fiche métier vient d'être ajoutée pour le métier <strong>" . $libelle . "</strong>.<br/> " .
                    "Vous pouvez modifier celle-ci en utilisant le lien suivant : <a href='" . $this->url()->fromRoute('fiche-metier/modifier', ['fiche-metier' => $fiche->getId()], [], true) . "'>Modification de la fiche métier #" . $fiche->getId() . "</a>");
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une fiche metier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $missions = $this->getActiviteService()->getActivitesByFicheMetierType($fichemetier);
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);

        return new ViewModel([
            'fiche' => $fichemetier,
            'missions' => $missions,
            'competences' => $competences,
            'applications' => $applications,
        ]);
    }

    public function historiserAction() : Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->historise($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        //todo pointer vers la nouvelle route
        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->restore($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        //todo pointer vers la nouvelle route
        return $this->redirect()->toRoute('fiche-metier-type', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getFicheMetierService()->delete($fichemetier);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fichemetier !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la fiche métier " . (($fichemetier and $fichemetier->getMetier()) ? $fichemetier->getMetier()->getLibelle() : "[Aucun métier]"),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/supprimer', ["fiche-metier" => $fichemetier->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** AUTRE MANIPULATION ********************************************************************************************/

    public function dupliquerAction()
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        if ($fichemetier !== null) {
            $duplicata = $this->getFicheMetierService()->dupliquerFicheMetier($fichemetier);
            return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $duplicata->getId()], [], true);
        }
        $vm = new ViewModel([
            'title' => "Un problème est survenu lors de la duplication de la fiche",
            'text' => "Un problème est survenu lors de la duplication de la fiche : <strong>fiche non trouvée</strong>",
            /** @see \Application\Controller\FicheMetierController::indexAction() */
            'retour' => $this->url()->fromRoute('fiche-metier-type', [], [], true),
        ]);
        $vm->setTemplate('default/probleme');
        return $vm;
    }

    public function importerAction()
    {
        $form = $this->getFicheMetierImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/importer', ['mode' => 'preview', 'path' => null], [], true));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];

            $csvInfos = $this->getFicheMetierService()->genererInfosFromCSV($fichier_path);

            if ($mode !== null) {
                if ($mode === 'import') {
                    if ($csvInfos['metier'] !== null and empty($csvInfos['competences']['Manquantes'])) {
                        $fiche = $this->getFicheMetierService()->importFromCsvArray($csvInfos);

                        /** @see \Application\Controller\FicheMetierController::afficherAction() */
                        return $this->redirect()->toRoute('fiche-metier/afficher', ['fiche-metier' => $fiche->getId()], [], true);
                    }
                }
                return new ViewModel([
                    'fichier_path' => $fichier_path,
                    'form' => $form,
                    'mode' => $mode,
                    'code' => $csvInfos['code'],
                    'metier' => $csvInfos['metier'],
                    'mission' => $csvInfos['mission'],
                    'activites' => $csvInfos['activites'],
                    'applications' => $csvInfos['applications'],
                    'competences' => $csvInfos['competences'],
                ]);
            }
        }

        $vm = new ViewModel([
            'title' => "Importation d'une fiche métier",
            'form' => $form,
        ]);
        return $vm;

    }

    public function exporterAction() : string
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        return $this->getFicheMetierService()->exporter($fichemetier);

    }

    /** COMPOSITION FICHE *********************************************************************************************/

    public function modifierEtatAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionEtatForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-etat', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);
        $form->reinit(FicheMetierEtats::TYPE);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Changer l'état de la fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierMetierAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionnerMetierForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-metier', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                $this->flashMessenger()->addSuccessMessage("Mise à jour du métier associé.");
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier le métier associé à la fiche métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierRaisonAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getRaisonForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-raison', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                $this->flashMessenger()->addSuccessMessage("Mise à jour de la \"raison d'être du métier\" effectuée.");
                exit();
            }
        }

        $vm =  new ViewModel([
            'title' => "Modification de la raison d'être du métier",
            'form' => $form,
            'info' => "Laisser vide si aucune raison n'est nécessaire",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }
}