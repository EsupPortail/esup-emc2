<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Application\Provider\Etat\FicheMetierEtats;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Carriere\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleFormAwareTrait;
use Carriere\Form\SelectionnerNiveauCarriere\SelectionnerNiveauCarriereFormAwareTrait;
use Element\Entity\Db\CompetenceType;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Form\CodeEmploiType\CodeEmploiTypeFormAwareTrait;
use FicheMetier\Form\CodeFonction\CodeFonctionFormAwareTrait;
use FicheMetier\Form\FicheMetierIdentification\FicheMetierIdentificationFormAwareTrait;
use FicheMetier\Form\Raison\RaisonFormAwareTrait;
use FicheMetier\Form\SelectionnerActivites\SelectionnerActivitesFormAwareTrait;
use FicheMetier\Form\SelectionnerMissionPrincipale\SelectionnerMissionPrincipaleFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\ActiviteElement\ActiviteElementServiceAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionElement\MissionElementServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FicheMetier\Service\TendanceElement\TendanceElementServiceAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementServiceAwareTrait;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use RuntimeException;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FicheMetierController extends AbstractActionController
{
    use ActiviteElementServiceAwareTrait;
    use AgentServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use MissionElementServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use TendanceElementServiceAwareTrait;
    use TendanceTypeServiceAwareTrait;
    use ThematiqueElementServiceAwareTrait;
    use ThematiqueTypeServiceAwareTrait;

    use CodeFonctionFormAwareTrait;
    use CodeEmploiTypeFormAwareTrait;
    use FicheMetierIdentificationFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use RaisonFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SelectionnerActivitesFormAwareTrait;
    use SelectionnerFamilleProfessionnelleFormAwareTrait;
    use SelectionnerNiveauCarriereFormAwareTrait;
    use SelectionnerMissionPrincipaleFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $displayCodeFonction = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);

        $referentiels = $this->getReferentielService()->getReferentiels();
        $etatTypes = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(FicheMetierEtats::TYPE);

        $codesFonctions = $displayCodeFonction ? $this->getCodeFonctionService()->getCodesFonctions() : null;

        $fichesMetiers = $this->getFicheMetierService()->getFichesMetiersWithFiltre($params);

        return new ViewModel([
            'params' => $params,
            'etatTypes' => $etatTypes,
            'fiches' => $fichesMetiers,
            'referentiels' => $referentiels,
            'displayCodeFonction' => $displayCodeFonction,
            'codesFonctions' => $codesFonctions,
        ]);
    }

    /** CRUD **********************************************************************************************************/

    public function afficherAction(): ViewModel|Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        if ($fichemetier === null) {
            $this->flashMessenger()->addErrorMessage("<strong>La fiche métier #" . $this->params()->fromRoute('fiche-metier') . " n'existe pas.</strong><br>Basculement sur l'index des fiches métiers.");
            return $this->redirect()->toRoute('fiche-metier', [], [], true);
        }
        $missions = $fichemetier->getMissions();
        $activites = $fichemetier->getActivites();
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);
        $competencesSpecifiques = $this->getFicheMetierService()->getCompetencesSpecifiquesDictionnaires($fichemetier, true);


        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes();
        $tendancesElements = $this->getTendanceElementService()->getTendancesElementsByFicheMetier($fichemetier);
        $thematiquestypes = $this->getThematiqueTypeService()->getThematiquesTypes();
        $thematiqueselements = $this->getThematiqueElementService()->getThematiquesElementsByFicheMetier($fichemetier);


        $vm = new ViewModel([
            'fiche' => $fichemetier,
            'types' => $this->getCompetenceTypeService()->getCompetencesTypes(true, 'ordre', 'ASC'),
            'missions' => $missions,
            'activites' => $activites,
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

        $form = $this->getModifierLibelleForm();
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

                $this->flashMessenger()->addSuccessMessage(
                    "Une nouvelle fiche métier vient d'être ajoutée pour le métier <strong>" . $fiche->getLibelle() . "</strong>.<br/> " .
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
            $this->flashMessenger()->addErrorMessage("<strong>La fiche métier #" . $this->params()->fromRoute('fiche-metier') . " n'existe pas.</strong><br>Basculement en création de fiche métier.");
            return $this->redirect()->toRoute('fiche-metier/ajouter', [], [], true);
        }
        $missions = $fichemetier->getMissions();
        $activites = $fichemetier->getActivites();
        $applications = $this->getFicheMetierService()->getApplicationsDictionnaires($fichemetier, true);
        $competences = $this->getFicheMetierService()->getCompetencesDictionnaires($fichemetier, true);
        $competencesSpecifiques = $this->getFicheMetierService()->getCompetencesSpecifiquesDictionnaires($fichemetier, true);

        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes();
        $tendancesElements = $this->getTendanceElementService()->getTendancesElementsByFicheMetier($fichemetier);
        $thematiquestypes = $this->getThematiqueTypeService()->getThematiquesTypes();
        $thematiqueselements = $this->getThematiqueElementService()->getThematiquesElementsByFicheMetier($fichemetier);

        $vm = new ViewModel([
            'fiche' => $fichemetier,
            'types' => $this->getCompetenceTypeService()->getCompetencesTypes(true, 'ordre', 'ASC'),
            'missions' => $missions,
            'activites' => $activites,
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
                'title' => "Suppression de la fiche métier " . $fichemetier->getLibelle(),
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

    public function modifierIdentificationAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $form = $this->getFicheMetierIdentificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-identification', ['fiche-metier' => $fichemetier->getId()], [], true));
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
            'title' => "Modifier l'identification de la fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }

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

    public function modifierCodeFonctionAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $codeFonction = $fichemetier->getCodeFonction();
        if ($codeFonction === null) {
            $codeFonction = new CodeFonction();
        }

        $form = $this->getCodeFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-code-fonction', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($codeFonction);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $existingCode = $this->getCodeFonctionService()->getCodeFonctionByNiveauAndFamille($codeFonction->getNiveauFonction(), $codeFonction->getFamilleProfessionnelle());
                if ($existingCode) {
                    $fichemetier->setCodeFonction($existingCode);
                } else {
                    $this->getCodeFonctionService()->create($codeFonction);
                    $fichemetier->setCodeFonction($codeFonction);
                }
                $fichemetier->setFamilleProfessionnelle($codeFonction->getFamilleProfessionnelle());
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

    public function supprimerCodeFonctionAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $fichemetier->setCodeFonction(null);
        $this->getFicheMetierService()->update($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
    }

    public function modifierCodeEmploiTypeAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getCodeEmploiTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-code-emploi-type', ['fiche-metier' => $fichemetier->getId()], [], true));
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
            'title' => "Modification des emploi-types associés",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerCodeEmploiTypeAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $fichemetier->setCodesEmploiType(null);
        $this->getFicheMetierService()->update($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
    }

    public function modifierFamilleProfessionnelleAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionnerFamilleProfessionnelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-famille-professionnelle', ['fiche-metier' => $fichemetier->getId()], [], true));
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
            'title' => "Modification de la famille professionnelle",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerFamilleProfessionnelleAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $fichemetier->setFamilleProfessionnelle(null);
        $this->getFicheMetierService()->update($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
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

    public function modifierNiveauCarriereAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionnerNiveauCarriereForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/modifier-niveau-carriere', ['fiche-metier' => $fichemetier->getId()], [], true));
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
            'title' => "Sélectionner le niveau de carrière associé",
            'fichemetier' => $fichemetier,
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerNiveauCarriereAction(): Response
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $fichemetier->setNiveauCarriere(null);
        $this->getFicheMetierService()->update($fichemetier);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('fiche-metier/modifier', ['fiche-metier' => $fichemetier->getId()], [], true);
    }


    /** GESTIONS DES BLOCS ********************************************************************************************/

    public function gererActivitesAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionnerActivitesForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/gerer-activites', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if (!isset($data['activites'])) $data['activites'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                foreach ($fichemetier->getActivites() as $activite) {
                    if ($activite->getId() === null) $this->getActiviteElementService()->create($activite);
                }
                $this->getFicheMetierService()->update($fichemetier);
            }
        }

        $css = <<<EOS
.dropdown-item:hover span.text span.activite span.description { 
    display: block !important; font-style: italic; 
}    

.dropdown-item:hover span.text span.activite span.full { 
    display: block !important;  
}
.dropdown-item:hover span.text span.activite span.shorten { 
    display: none !important;  
}

span.activite {    
    display: inline-block;
}

.bootstrap-select .filter-option-inner {
    white-space: normal;
    height: auto;
}
EOS;

        $vm = new ViewModel([
            'title' => "Gestion des activités associées à la fiche métier",
            'form' => $form,
            'css' => $css,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function bougerActiviteAction(): JsonModel
    {
        $activiteElement = $this->getActiviteElementService()->getResquestedActiviteElement($this);
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $direction = $this->params()->fromRoute('direction');

        $this->getActiviteElementService()->move($ficheMetier, $activiteElement, $direction);
        $this->getActiviteElementService()->reorder($ficheMetier);

        return new JsonModel(['return' => true]);
    }

    public function retirerActiviteAction(): JsonModel
    {
        $activiteElement = $this->getActiviteElementService()->getResquestedActiviteElement($this);
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $ficheMetier->removeActivite($activiteElement);
        $this->getFicheMetierService()->update($ficheMetier);
        $this->getActiviteElementService()->delete($activiteElement);

        $this->getActiviteElementService()->reorder($ficheMetier);

        return new JsonModel(['return' => true]);
    }

    public function gererMissionsPrincipalesAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $form = $this->getSelectionnerMissionPrincipaleForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/gerer-missions-principales', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if (!isset($data['missions'])) $data['missions'] = [];
            $form->setData($data);
            if ($form->isValid()) {
                foreach ($fichemetier->getMissions() as $mission) {
                    if ($mission->getId() === null) $this->getMissionElementService()->create($mission);
                }
                $this->getFicheMetierService()->update($fichemetier);
            }
        }

        $css = <<<EOS
.dropdown-item:hover span.text span.mission span.description { 
    display: block !important; font-style: italic; 
}    

.dropdown-item:hover span.text span.mission span.full { 
    display: block !important;  
}
.dropdown-item:hover span.text span.mission span.shorten { 
    display: none !important;  
}


span.mission {    
    display: inline-block;
}

.bootstrap-select .filter-option-inner {
    white-space: normal;
    height: auto;
}
EOS;

        $vm = new ViewModel([
            'title' => "Gestion des missions principales associées à la fiche métier",
            'form' => $form,
            'css' => $css,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function bougerMissionAction(): JsonModel
    {
        $missionElement = $this->getMissionElementService()->getResquestedMissionElement($this);
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $direction = $this->params()->fromRoute('direction');

        $this->getMissionElementService()->move($ficheMetier, $missionElement, $direction);
        $this->getMissionElementService()->reorder($ficheMetier);

        return new JsonModel(['return' => true]);
    }

    public function retirerMissionAction(): JsonModel
    {
        $missionElement = $this->getMissionElementService()->getResquestedMissionElement($this);
        $ficheMetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

        $ficheMetier->removeMission($missionElement);
        $this->getFicheMetierService()->update($ficheMetier);
        $this->getMissionElementService()->delete($missionElement);

        $this->getActiviteElementService()->reorder($ficheMetier);

        return new JsonModel(['return' => true]);
    }

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

        $css = <<<EOS
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

        $css = <<<EOS
.dropdown-item:hover span.text span.competence span.description { 
    display: block !important; font-style: italic; 
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
        $type = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_SPECIFIQUE);

        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/gerer-competences-specifiques', ['fiche-metier' => $fichemetier->getId()], [], true));
        $form->bind($fichemetier);

        $form->reinit($type);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheMetierService()->update($fichemetier);
                exit();
            }
        }

        $css = <<<EOS
.dropdown-item:hover span.text span.competence span.description {
    display: block !important; font-style: italic;
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

    public function refreshRaisonAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $mode = $this->params()->fromRoute('mode');

        $vm = new ViewModel([
            'fichemetier' => $fichemetier,
            'mode' => $mode,
        ]);
        $vm->setTemplate('fiche-metier/fiche-metier/partial/bloc-raison');
        return $vm;
    }

    public function refreshMissionsAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $missions = $fichemetier->getMissions();
        $mode = $this->params()->fromRoute('mode');

        $vm = new ViewModel([
            'fichemetier' => $fichemetier,
            'missions' => $missions,
            'mode' => $mode,
        ]);
        $vm->setTemplate('fiche-metier/fiche-metier/partial/bloc-mission');
        return $vm;
    }

    public function refreshActivitesAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $activites = $fichemetier->getActivites();
        $mode = $this->params()->fromRoute('mode');

        $vm = new ViewModel([
            'fichemetier' => $fichemetier,
            'activites' => $activites,
            'mode' => $mode,
        ]);
        $vm->setTemplate('fiche-metier/fiche-metier/partial/bloc-activite');
        return $vm;
    }


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
        $vm->setTemplate('fiche-metier/fiche-metier/partial/bloc-applications');
        return $vm;
    }

    public function refreshCompetencesAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');

//        $typeLibelle = str_replace("_"," ", $this->params()->fromRoute('type'));
//        $type = $this->getCompetenceTypeService()->getCompetenceTypeByLibelle($typeLibelle);
        $typeCode = str_replace("_", " ", $this->params()->fromRoute('type'));
        $type = $this->getCompetenceTypeService()->getCompetenceTypeByCode($typeCode);

        if ($type == null) {
            throw new RuntimeException("Aucun type de compétences avec le code [" . $typeCode . "]", -1);
        }

        $competences = $this->getFicheMetierService()->getCompetencesDictionnairesByType($fichemetier, $type, true);

        $vm = new ViewModel([
            'fichemetier' => $fichemetier,
            'competences' => $competences,
            'type' => $type,
            'mode' => $this->params()->fromRoute('mode'),
        ]);
        $vm->setTemplate('fiche-metier/refresh-competences');
        return $vm;
    }

    public function listerAgentsAction(): ViewModel
    {
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $fichespostes = $this->getFichePosteService()->getFichesPostesByFicheMetier($fichemetier);

        return new ViewModel([
            'title' => "Liste des agents ayant la fiche métier <strong>" . $fichemetier->getLibelle() . "</strong>",
            'fichemetier' => $fichemetier,
            'fichespostes' => $fichespostes,
        ]);
    }
}