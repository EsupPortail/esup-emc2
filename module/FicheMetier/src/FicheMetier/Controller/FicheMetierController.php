<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Provider\Etat\FicheMetierEtats;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use FicheMetier\Form\Raison\RaisonFormAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class FicheMetierController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use MetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;

    use FicheMetierImportationFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use RaisonFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SelectionnerMetierFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $fromQueries = $this->params()->fromQuery();
        $etatId = $fromQueries['etat'] ?? null;
        $domaineId = $fromQueries['domaine'] ?? null;
        $expertise = $fromQueries['expertise'] ?? null;
        $params = ['etat' => $etatId, 'domaine' => $domaineId, 'expertise' => $expertise];

        $etatTypes = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(FicheMetierEtats::TYPE);
        $domaines = $this->getDomaineService()->getDomaines();

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiersWithFiltre($params);

        return new ViewModel([
            'params' => $params,
            'domaines' => $domaines,
            'etatTypes' => $etatTypes,
            'fiches' => $fichesMetiers,
        ]);
    }

    /** CRUD **********************************************************************************************************/

    public function afficherAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $missions = $fichemetier->getMissions() ;
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences =  $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);

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
        $missions = $fichemetier->getMissions();
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
        return $this->redirect()->toRoute('fiche-metier', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->restore($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $fiches = $this->getFichePosteService()->getFichesPostesByFicheMetier($fichemetier);

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
                'warning' => !empty($fiches)?"Attention : ".count($fiches). " fiche·s de poste dépende·nt de cette fiche métier":null,
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-metier/supprimer', ["fiche-metier" => $fichemetier->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** AUTRE MANIPULATION ********************************************************************************************/

    public function dupliquerAction(): ViewModel|Response
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
            'retour' => $this->url()->fromRoute('fiche-metier', [], [], true),
        ]);
        $vm->setTemplate('default/probleme');
        return $vm;
    }

    public function importerAction(): ViewModel|Response
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

                        /** @see FicheMetierController::afficherAction() */
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

    public function modifierExpertiseAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        if ($fichemetier->hasExpertise()) {
            $fichemetier->setExpertise(false);
        } else {
            $fichemetier->setExpertise(true);
        }
        $this->getFicheMetierService()->update($fichemetier);

        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
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

    /** GESTION DES MISSIONS ******************************************************************************************/

    public function ajouterMissionAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $mission = new Mission();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/ajouter-mission', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->create($mission);
                $this->getFicheMetierService()->addMission($fichemetier, $mission);
                $this->getFicheMetierService()->compressMission($fichemetier);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une mission',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerMissionAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $this->getFicheMetierService()->removeMission($fichemetier, $mission);
        $this->getFicheMetierService()->compressMission($fichemetier);

        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
    }

    public function deplacerMissionAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $direction = $this->params()->fromRoute('direction');

        $this->getFicheMetierService()->compressMission($fichemetier);
        $this->getFicheMetierService()->moveMission($fichemetier, $mission, $direction);

        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
    }

    /** GESTION DES APPLICATIONS ET DES COMPETENTES *******************************************************************/

    public function gererApplicationsAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/gerer-applications', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gestion des applications associées à la fiche métier",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererCompetencesAction() : ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/gerer-competences', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gestion des compétences associées à la fiche métier",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }


    /** LEFT OVER *****************************************************************************************************/

//    public function clonerApplicationsAction(): ViewModel
//    {
//        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
//
//        $form = $this->getSelectionFicheMetierForm();
//        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/cloner-applications', ['fiche-metier' => $ficheMetier->getId()], [], true));
//
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $data = $request->getPost();
//            $ficheClone = $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']);
//
//            if ($ficheClone !== null) {
//                try {
//                    /** @var CompetenceElement[] $oldCollection */
//                    $oldCollection = $ficheMetier->getApplicationCollection();
//                    foreach ($oldCollection as $element) $element->historiser();
//
//                    $newCollection = $ficheClone->getApplicationListe();
//                    foreach ($newCollection as $element) {
//                        $newElement = new ApplicationElement();
//                        $newElement->setApplication($element->getApplication());
//                        $newElement->setCommentaire("Clonée depuis la fiche #" . $ficheClone->getId());
//                        $newElement->setNiveauMaitrise($element->getNiveauMaitrise());
//                        $this->getFicheMetierService()->getEntityManager()->persist($newElement);
//                        $ficheMetier->addApplicationElement($newElement);
//                    }
//                    $this->getFicheMetierService()->getEntityManager()->flush();
//                } catch (ORMException $e) {
//                    throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
//                }
//            }
//
//        }
//
//        $vm = new ViewModel();
//        $vm->setVariables([
//            'title' => "Cloner les applications d'une autre fiche métier",
//            'form' => $form,
//        ]);
//        return $vm;
//    }
//
//    public function clonerCompetencesAction(): ViewModel
//    {
//        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
//
//        $form = $this->getSelectionFicheMetierForm();
//        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/cloner-competences', ['fiche-metier' => $ficheMetier->getId()], [], true));
//
//        $request = $this->getRequest();
//        if ($request->isPost()) {
//            $data = $request->getPost();
//            $ficheClone = $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']);
//
//            if ($ficheClone !== null) {
//                try {
//                    /** @var CompetenceElement[] $oldCollection */
//                    $oldCollection = $ficheMetier->getCompetenceCollection();
//                    foreach ($oldCollection as $element) $element->historiser();
//
//                    $newCollection = $ficheClone->getCompetenceListe();
//                    foreach ($newCollection as $element) {
//                        $newElement = new CompetenceElement();
//                        $newElement->setCompetence($element->getCompetence());
//                        $newElement->setCommentaire("Clonée depuis la fiche #" . $ficheClone->getId());
//                        $newElement->setNiveauMaitrise($element->getNiveauMaitrise());
//                        $this->getFicheMetierService()->getEntityManager()->persist($newElement);
//                        $ficheMetier->addCompetenceElement($newElement);
//                    }
//                    $this->getFicheMetierService()->getEntityManager()->flush();
//                } catch (ORMException $e) {
//                    throw new RuntimeException("Un problème est survenu en base de donnée", 0, $e);
//                }
//            }
//
//        }
//
//        $vm = new ViewModel();
//        $vm->setVariables([
//            'title' => "Cloner les compétences d'une autre fiche métier",
//            'form' => $form,
//        ]);
//        return $vm;
//    }
}