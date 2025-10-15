<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Provider\Etat\FicheMetierEtats;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Form\CodeFonction\CodeFonctionFormAwareTrait;
use FicheMetier\Form\Raison\RaisonFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FicheMetier\Service\TendanceElement\TendanceElementServiceAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementServiceAwareTrait;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerMetier\SelectionnerMetierFormAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use RuntimeException;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FicheMetierController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use MetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use TendanceElementServiceAwareTrait;
    use TendanceTypeServiceAwareTrait;
    use ThematiqueElementServiceAwareTrait;
    use ThematiqueTypeServiceAwareTrait;

    use CodeFonctionFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use RaisonFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SelectionnerMetierFormAwareTrait;

    public function indexAction(): ViewModel
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

    public function afficherAction(): ViewModel|Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        if ($fichemetier === null) {
            $this->flashMessenger()->addErrorMessage("<strong>La fiche métier #".$this->params()->fromRoute('fiche-metier'). " n'existe pas.</strong><br>Basculement sur l'index des fiches métiers.");
            return $this->redirect()->toRoute('fiche-metier', [], [], true);
        }
        $missions = $fichemetier->getMissions();
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);
        $competencesSpecifiques = $this->getFicheMetierService()->getCompetencesSpecifiquesDictionnaires($fichemetier, true);


        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes();
        $tendancesElements = $this->getTendanceElementService()->getTendancesElementsByFicheMetier($fichemetier);
        $thematiquestypes = $this->getThematiqueTypeService()->getThematiquesTypes();
        $thematiqueselements = $this->getThematiqueElementService()->getThematiquesElementsByFicheMetier($fichemetier);

        $vm = new ViewModel([
            'fiche' => $fichemetier,
            'missions' => $missions,
            'competences' => $competences,
            'competencesSpecifiques' => $competencesSpecifiques,
            'applications' => $applications,
            'tendancesTypes' => $tendancesTypes,
            'tendancesElements' => $tendancesElements,
            'thematiquestypes' => $thematiquestypes,
            'thematiqueselements' => $thematiqueselements,

            'parametres' => $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE),
            'mode' => 'affichage',
        ]);
        $vm->setTemplate('fiche-metier/fiche-metier/fiche-metier');
        return $vm;
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

    public function modifierAction(): ViewModel|Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        if ($fichemetier === null) {
            $this->flashMessenger()->addErrorMessage("<strong>La fiche métier #".$this->params()->fromRoute('fiche-metier'). " n'existe pas.</strong><br>Basculement en création de fiche métier.");
            return $this->redirect()->toRoute('fiche-metier/ajouter', [], [], true);
        }
        $missions = $fichemetier->getMissions();
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);
        $competencesSpecifiques = $this->getFicheMetierService()->getCompetencesSpecifiquesDictionnaires($fichemetier, true);

        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes();
        $tendancesElements = $this->getTendanceElementService()->getTendancesElementsByFicheMetier($fichemetier);
        $thematiquestypes = $this->getThematiqueTypeService()->getThematiquesTypes();
        $thematiqueselements = $this->getThematiqueElementService()->getThematiquesElementsByFicheMetier($fichemetier);

        $vm = new ViewModel([
            'fiche' => $fichemetier,
            'missions' => $missions,
            'competences' => $competences,
            'competencesSpecifiques' => $competencesSpecifiques,
            'applications' => $applications,
            'tendancesTypes' => $tendancesTypes,
            'tendancesElements' => $tendancesElements,
            'thematiquestypes' => $thematiquestypes,
            'thematiqueselements' => $thematiqueselements,

            'parametres' => $this->getParametreService()->getParametresByCategorieCode(FicheMetierParametres::TYPE),
            'mode' => 'edition-fiche-metier',
        ]);
        $vm->setTemplate('fiche-metier/fiche-metier/fiche-metier');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->historise($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $this->getFicheMetierService()->restore($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier', [], [], true);
    }

    public function supprimerAction(): ViewModel
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
                'warning' => !empty($fiches) ? "Attention : " . count($fiches) . " fiche·s de poste dépende·nt de cette fiche métier" : null,
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

    public function exporterAction(): string
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        return $this->getFicheMetierService()->exporter($fichemetier);

    }

    /** COMPOSITION FICHE *********************************************************************************************/

    public function modifierEtatAction(): ViewModel
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

    public function modifierCodeFonctionAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $form = $this->getCodeFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-code-fonction', ['fiche-metier' => $fichemetier->getId()], [], true));
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
            'title' => "Modification du code fonction",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierMetierAction(): ViewModel
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

    public function modifierRaisonAction(): ViewModel
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

        $vm = new ViewModel([
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

    public function gererApplicationsAction(): ViewModel
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

        $css=<<<EOS
.dropdown-item:hover span.text span.competence span.description { 
    display: block !important; font-style: italic; 
}    

span.application {    
    display: inline-block;
}

.bootstrap-select .filter-option-inner {
    white-space: normal;
    height: auto;
}
EOS;

        $vm = new ViewModel([
            'title' => "Gestion des applications associées à la fiche métier",
            'form' => $form,
            'css' => $css,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererCompetencesAction(): ViewModel
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

        $css=<<<EOS
.dropdown-item:hover span.text span.competence span.description { 
    display: block !important; font-style: italic; 
}    

span.competence {    
    display: inline-block;
}

.bootstrap-select .filter-option-inner {
    white-space: normal;
    height: auto;
}
EOS;

        $vm = new ViewModel([
            'title' => "Gestion des compétences associées à la fiche métier",
            'form' => $form,
            'js' => null,
            'css' => $css,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }


    public function gererCompetencesSpecifiquesAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/gerer-competences-specifiques', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->getHydrator()->setCollection($fichemetier->getCompetencesSpecifiquesCollection());
        $form->bind($fichemetier);


        //todo modifier hydrator
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                exit();
            }
        }

        $css=<<<EOS
.dropdown-item:hover span.text span.competence span.description { 
    display: block !important; font-style: italic; 
}    

span.competence {    
    display: inline-block;
}

.bootstrap-select .filter-option-inner {
    white-space: normal;
    height: auto;
}
EOS;

        $vm = new ViewModel([
            'title' => "Gestion des compétences spécifiques associées à la fiche métier",
            'form' => $form,
            'css' => $css,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    /** ACTIONS POUR LE RAFRAICHISSEMENT SUR PLACE ********************************************************************/

    public function refreshApplicationsAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $mode = $this->params()->fromRoute('mode');

        $vm = new ViewModel([
            'fichemetier' => $fichemetier,
            'applications' => $applications,
            'mode' => $mode,
        ]);
        $vm->setTemplate('fiche-metier/refresh-applications');
        return $vm;
    }

    public function listerAgentsAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $metier = $fichemetier->getMetier();

        if ($metier === null) {
            throw new RuntimeException("Aucun métier pour la fiche métier #".$fichemetier->getId(),-1);
        }

        $array = $this->getMetierService()->getInfosAgentsByMetier($metier);
        $agentIds = [];
        foreach ($array as $item) {
            $agentIds[$item['c_individu']] = $item['c_individu'];
        }
        $agents = $this->getAgentService()->getAgentsByIds($agentIds);

        return new ViewModel([
            'title' => "Liste des agents ayant la fiche métier #".$metier->getId(),
            'fiche' => $fichemetier,
            'metier' => $metier,
            'agents' => $agents,
            'array' => $array,
        ]);
    }
}