<?php

namespace Application\Controller;

use Application\Entity\Db\AgentSuperieur;
use Application\Entity\Db\Expertise;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use Application\Entity\Db\FicheTypeExterne;
use Application\Entity\Db\SpecificitePoste;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierFormAwareTrait;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\AssocierTitre\AssocierTitreFormAwareTrait;
use Application\Form\Expertise\ExpertiseFormAwareTrait;
use Application\Form\Rifseep\RifseepFormAwareTrait;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Form\SpecificitePoste\SpecificitePosteFormAwareTrait;
use Application\Provider\Etat\FichePosteEtats;
use Application\Provider\Template\PdfTemplate;
use Application\Provider\Validation\FichePosteValidations;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesServiceAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentPoste\AgentPosteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesServiceAwareTrait;
use Application\Service\CompetencesRetirees\CompetencesRetireesServiceAwareTrait;
use Application\Service\Expertise\ExpertiseServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\Notification\NotificationServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use DateTime;
use FicheMetier\Entity\Db\MissionActivite;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use Mpdf\MpdfException;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatFormAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FichePosteController extends AbstractActionController
{
    /** Trait utilitaire */

    /** Service **/
    use ActivitesDescriptionsRetireesServiceAwareTrait;
    use AgentServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use AgentPosteServiceAwareTrait;
    use ApplicationsRetireesServiceAwareTrait;
    use CompetencesRetireesServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use ExpertiseServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StructureServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;

    /** Form **/
    use AjouterFicheMetierFormAwareTrait;
    use AssocierTitreFormAwareTrait;
    use ExpertiseFormAwareTrait;
    use RifseepFormAwareTrait;
    use SelectionEtatFormAwareTrait;
    use SpecificitePosteFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $fiches = $this->getFichePosteService()->getFichesPostesAsArray();

        $fichesCompletes = [];
        $fichesIncompletes = [];
        $ficheVides = [];
        foreach ($fiches as $fiche) {
            if ($fiche['agent_id'] !== null and $fiche['fiche_principale'] !== null) $fichesCompletes[] = $fiche;
            else {
                if ($fiche['agent_id'] === null and $fiche['fiche_principale'] === null) $ficheVides[] = $fiche;
                else $fichesIncompletes[] = $fiche;
            }
        }

        return new ViewModel([
            'fiches' => $fiches,
            'fichesIncompletes' => $fichesIncompletes,
            'fichesVides' => $ficheVides,
            'fichesCompletes' => $fichesCompletes,
        ]);
    }

    public function ajouterAction(): Response
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        if ($agent) {
            $fiche = $this->getFichePosteService()->getFichePosteByAgent($agent);
            if ($fiche !== null) {
                $this->flashMessenger()->addErrorMessage("La fiche de poste existe déjà l'ajout n'a pu être effectué.");
                return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], [], true);
            }
        }

        $fiche = new FichePoste();
        $fiche->setAgent($agent);
        $this->getFichePosteService()->create($fiche);
        $this->getEtatInstanceService()->setEtatActif($fiche,FichePosteEtats::ETAT_CODE_REDACTION);
        $this->getFichePosteService()->update($fiche);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], [], true);
    }

    public function dupliquerAction(): ViewModel|Response
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        if ($agent !== null) {
            $fiche = $this->getFichePosteService()->getFichePosteByAgent($agent);
            if ($fiche !== null) {
                $this->flashMessenger()->addErrorMessage("La fiche de poste existe déjà la duplication n'a pu être effectuée.");
                return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], [], true);
            }
        }

        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        $fiches = $this->getFichePosteService()->getFichesPostesByStructuresAndAgent($structures, true, $agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $ficheId = ($data['fiche'] !== 'null') ? $data['fiche'] : null;
            $fiche = $this->getFichePosteService()->getFichePoste($ficheId);

            if ($fiche !== null) {
                $nouvelleFiche = $this->getFichePosteService()->clonerFichePoste($fiche, false);
            } else {
                $nouvelleFiche = new FichePoste();
                $this->getFichePosteService()->create($nouvelleFiche);
            }

            $nouvelleFiche->setAgent($agent);
            $this->getEtatInstanceService()->setEtatActif($nouvelleFiche,FichePosteEtats::ETAT_CODE_REDACTION);
            $this->getFichePosteService()->update($nouvelleFiche);

            /**  Commenter pour eviter perte de temps et clignotement de la fenêtre modal */
            return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $nouvelleFiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
            //exit();
        }

        return new ViewModel([
            'title' => "Duplication d'une fiche de poste pour [" . $agent->getDenomination() . "]",
            'structure' => $structure,
            'agent' => $agent,
            'fiches' => $fiches,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);

        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);

        $titre = 'Fiche de poste <br/>';
        $titre .= '<strong>';
        if ($fiche->getFicheTypeExternePrincipale()) {
            $titre .= $fiche->getFicheTypeExternePrincipale()->getFicheType()->getMetier()->getLibelle();
        } else {
            $titre .= "<span class='icon icon-attention' style='color:darkred;'></span> Aucun fiche principale";
        }
        if ($fiche->getLibelle() !== null) {
            $titre .= "(" . $fiche->getLibelle() . ")";
        }
        $titre .= '</strong>';

        $applications = $this->getFichePosteService()->getApplicationsDictionnaires($fiche);
        $competences = $this->getFichePosteService()->getCompetencesDictionnaires($fiche);
        $formations = $this->getFichePosteService()->getFormationsDictionnaires($fiche);
        $activites = $this->getFichePosteService()->getActivitesDictionnaires($fiche);

        //parcours de formation
        $parcours = $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($fiche);

        return new ViewModel([
            'title' => $titre,
            'fiche' => $fiche,
            'applications' => $applications,
            'competences' => $competences,
            'formations' => $formations,
            'activites' => $activites,
            'parcours' => $parcours,
            'structure' => $structure,
            'postes' => ($fiche->getAgent()) ? $this->getAgentPosteService()->getPostesAsAgent($fiche->getAgent()) : [],
        ]);
    }

    public function editerAction(): ViewModel|Response
    {
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);

        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        if ($fiche === null) $fiche = $this->getFichePosteService()->getLastFichePoste();

        if ($fiche->getEtatActif()->getType()->getCode() === FichePosteEtats::ETAT_CODE_SIGNEE) return $this->redirect()->toRoute('fiche-poste/afficher', ['structure' => $structure, 'fiche-poste' => $fiche->getId()], [], true);
        $agent = $fiche->getAgent();

        $applications = $this->getFichePosteService()->getApplicationsDictionnaires($fiche);
        $competences = $this->getFichePosteService()->getCompetencesDictionnaires($fiche);
        $activites = $this->getFichePosteService()->getActivitesDictionnaires($fiche);

        //TODO remettre en place après stabilisation
        $parcours = []; // $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($fiche);

        return new ViewModel([
            'ficheId' => $this->params()->fromRoute('fiche-poste'),
            'fiche' => $fiche,
            'agent' => $agent,
            'structure' => $structure,
            'applications' => $applications,
            'competences' => $competences,
            'activites' => $activites,
            'parcours' => $parcours,
            'postes' => ($fiche->getAgent()) ? $this->getAgentPosteService()->getPostesAsAgent($fiche->getAgent()) : [],
        ]);
    }

    public function historiserAction(): Response
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $this->getFichePosteService()->historise($fiche);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $this->getFichePosteService()->restore($fiche);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('fiche-poste', [], [], true);
    }

    public function detruireAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);

        $structureId = $this->params()->fromQuery('structure');
        $params = [];
        if ($structureId !== null) $params["structure"] = $structureId;

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getFichePosteService()->delete($fiche);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fiche !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la fiche de poste  de " . (($fiche->getAgent()) ? $fiche->getAgent()->getDenomination() : "[Aucun Agent]"),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-poste/detruire', ["fiche-poste" => $fiche->getId()], ["query" => $params], true),
            ]);
        }
        return $vm;
    }

    public function exporterAction(): string
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $ficheposte->getAgent();
        $ficheposte->addDictionnaire('applications', $this->getFichePosteService()->getApplicationsDictionnaires($ficheposte));
        $ficheposte->addDictionnaire('competences', $this->getFichePosteService()->getCompetencesDictionnaires($ficheposte));
        $ficheposte->addDictionnaire('formations', $this->getFichePosteService()->getFormationsDictionnaires($ficheposte));
        $ficheposte->addDictionnaire('parcours', $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($ficheposte));

        $vars = [
            'ficheposte' => $ficheposte,
            'agent' => $agent,
            'structure' => ($agent) ? $agent->getAffectationPrincipale()->getStructure() : null,
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplate::FICHE_POSTE, $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème lié à MPDF est survenue", 0, $e);
        }
    }


    /** GESTION DES ETATS DES FICHES POSTES ***************************************************************************/

    public function changerEtatAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);

        $form = $this->getSelectionEtatForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/changer-etat', ['fiche-poste' => $fiche->getId()], [], true));
        $form->bind($fiche);
        $form->reinit(FichePosteEtats::TYPE);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Changer l'état de la fiche de poste",
            'form' => $form,
        ]);
        return $vm;
    }

    /** TITRE *********************************************************************************************************/

    public function associerTitreAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);

        /** @var AssocierTitreForm $form */
        $form = $this->getAssocierTitreForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/associer-titre', ['fiche-poste' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->update($fiche);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Associer un titre',
            'form' => $form,
        ]);
        return $vm;
    }

    /** AGENT *********************************************************************************************************/

    public function associerAgentAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $structureId = $this->params()->fromQuery('structure');
        $structure = $this->getStructureService()->getStructure($structureId);

        /**@var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $agent = $this->getAgentService()->getAgent($data["agent"]["id"]);

            if ($agent !== null) {
                $fiche_old = $this->getFichePosteService()->getFichePosteByAgent($agent);
                if ($fiche_old !== null) {
                    $this->flashMessenger()->addErrorMessage("Cet agent est déjà associé à une fiche de poste active.");
                } else {
                    $fiche->setAgent($agent);
                    $this->getFichePosteService()->update($fiche);

                    /**
                     *  /!\ Attention /!\ : Si un agent est associé à une fiche de recrutement alors celle-ci n'est plus
                     *  une fiche de recrutement et doit être retirée de la liste de celles-ci.
                     */
                    if ($structure) {
                        $structures = $this->getStructureService()->getStructuresFilles($structure);
                        $structures[] = $structure;
                        foreach ($structures as $structureTMP) {
                            $structureTMP->removeFichePosteRecrutement($fiche);
                            $this->getStructureService()->update($structureTMP);
                        }
                    }
                }
            }
        }

        return new ViewModel([
            'title' => 'Associer un agent',
            'strcture' => $structure,
            'ficheposte' => $fiche,
        ]);
    }

    /** FICHE METIER **************************************************************************************************/

    public function ajouterFicheMetierAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $fiche->getAgent();

        $ficheTypeExterne = new FicheTypeExterne();
        $form = $this->getAjouterFicheTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/ajouter-fiche-metier', ['fiche-poste' => $fiche->getId()], [], true));
        $form->bind($ficheTypeExterne);

        if ($agent and !empty($agent->getGradesActifs())) $form->reinitWithAgent($agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            $res = $this->checkValidite($fiche, $data);
            if ($res) return $res;

            if ($form->isValid()) {
                $ficheTypeExterne->setFichePoste($fiche);
                $this->getFichePosteService()->createFicheTypeExterne($ficheTypeExterne);

                if ($ficheTypeExterne->getPrincipale()) {
                    foreach ($fiche->getFichesMetiers() as $ficheMetier) {
                        if ($ficheMetier !== $ficheTypeExterne && $ficheMetier->getPrincipale()) {
                            $ficheMetier->setPrincipale(false);
                            $this->getFichePosteService()->updateFicheTypeExterne($ficheMetier);
                        }
                    }
                }

                //comportement par defaut (ajout de toutes les activités)
                $missions = $ficheTypeExterne->getFicheType()->getMissions();
                $tab = [];
                foreach ($missions as $mission) {
                    $tab[] = $mission->getMission()->getId();
                }
                $text = implode(";", $tab);
                $ficheTypeExterne->setActivites($text);
                $this->getFichePosteService()->updateFicheTypeExterne($ficheTypeExterne);
            }
        }

        $vm = new ViewModel();
        $vm->setVariables([
            'title' => 'Ajout d\'une fiche métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFicheMetierAction(): ViewModel
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this);
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFichePosteService()->getFicheTypeExterne($ficheTypeExterneId);
        $previous = $ficheTypeExterne->getQuotite();

        $form = $this->getAjouterFicheTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/modifier-fiche-metier', ['fiche-poste' => $fichePoste->getId(), 'fiche-type-externe' => $ficheTypeExterne->getId()], [], true));
        $form->bind($ficheTypeExterne);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            $data["old"] = $previous;
            $res = $this->checkValidite($fichePoste, $data);
            if ($res) return $res;

            if ($form->isValid()) {
                $ficheTypeExterne->setFichePoste($fichePoste);
                $this->getFichePosteService()->updateFicheTypeExterne($ficheTypeExterne);

                if ($ficheTypeExterne->getPrincipale()) {
                    foreach ($fichePoste->getFichesMetiers() as $ficheMetier) {
                        if ($ficheMetier !== $ficheTypeExterne && $ficheMetier->getPrincipale()) {
                            $ficheMetier->setPrincipale(false);
                            $this->getFichePosteService()->updateFicheTypeExterne($ficheMetier);
                        }
                    }
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/fiche-poste/ajouter-fiche-metier');
        $vm->setVariables([
            'title' => 'Modification d\'une fiche métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerFicheMetierAction(): Response
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this);
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFichePosteService()->getFicheTypeExterne($ficheTypeExterneId);

        if ($ficheTypeExterne && $fichePoste) $this->getFichePosteService()->deleteFicheTypeExterne($ficheTypeExterne);

        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fichePoste->getId()], [], true);
    }

    public function selectionnerActiviteAction(): ViewModel
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this);
        $ficheTypeExterneId = $this->params()->fromRoute('fiche-type-externe');
        $ficheTypeExterne = $this->getFichePosteService()->getFicheTypeExterne($ficheTypeExterneId);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $result = [];
            foreach ($data as $key => $value) {
                if ($value === 'on') $result[] = $key;
            }
            $result = implode(";", $result);
            $ficheTypeExterne->setActivites($result);
            $this->getFichePosteService()->updateFicheTypeExterne($ficheTypeExterne);
        }

        return new ViewModel([
            'title' => 'Liste des activités de la fiche métier <br/> <strong>' . $ficheTypeExterne->getFicheType()->getMetier() . '</strong>',
            'fichePoste' => $fichePoste,
            'ficheTypeExterne' => $ficheTypeExterne,
        ]);
    }

    private function checkValidite(FichePoste $fiche, $data): ?ViewModel
    {
        $cut = false;
        if ($data['est_principale'] === "1" && ((int)$data['quotite']) < 50) {
            $cut = true;
            $this->flashMessenger()->addErrorMessage("La fichie métier principale doit avoir une quotité d'au moins 50%.");
        }
        if ($data['est_principale'] === "0" && ((int)$data['quotite']) > 50) {
            $cut = true;
            $this->flashMessenger()->addErrorMessage("La fichie métier non principale doit avoir une quotité d'au plus 50%.");
        }
        if ($fiche->getQuotiteTravaillee() + ((int)$data['quotite']) - ((int)$data['old']) > 100) {
            $cut = true;
            $this->flashMessenger()->addErrorMessage("La somme des quotités travaillées ne peut dépasser 100%.");
        }
        if ($cut) {
            return (new ViewModel(['title' => 'Informations saisies incorrectes']))->setTemplate('layout/flashMessage');
        }
        return null;
    }

    /** Applications et Compétences de la fiche de postes  ************************************************************/

    public function selectionnerApplicationsRetireesAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);

        /** @var array $applications */
        $applications = $this->getFichePosteService()->getApplicationsDictionnaires($ficheposte);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($applications as $item) {
                $application = $item['entite'];
                $checked = (isset($data[$application->getId()]) and $data[$application->getId()] === "on");

                if ($checked === false and $item['conserve'] === true) {
                    $this->getApplicationsRetireesService()->add($ficheposte, $application);
                }
                if ($checked === true and $item['conserve'] === false) {
                    $this->getApplicationsRetireesService()->remove($ficheposte, $application);
                }
            }
        }

        return new ViewModel([
            'title' => "Sélection des applications pour la fiche de poste",
            'ficheposte' => $ficheposte,
            'applications' => $applications,
        ]);
    }

    public function selectionnerCompetencesRetireesAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);

        /** @var array $competences */
        $competences = $this->getFichePosteService()->getCompetencesDictionnaires($ficheposte);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($competences as $item) {
                $competence = $item['entite'];
                $checked = (isset($data[$competence->getId()]) and $data[$competence->getId()] === "on");

                if ($checked === false and $item['conserve'] === true) {
                    $this->getCompetencesRetireesService()->add($ficheposte, $competence);
                }
                if ($checked === true and $item['conserve'] === false) {
                    $this->getCompetencesRetireesService()->remove($ficheposte, $competence);
                }
            }
        }

        return new ViewModel([
            'title' => "Sélection des formations pour la fiche de poste",
            'ficheposte' => $ficheposte,
            'competences' => $competences,
        ]);
    }

    /** Descriprition conservées **************************************************************************************/

    public function selectionnerDescriptionsRetireesAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $fichemetier = $this->getFicheMetierService()->getRequestedFicheMetier($this, 'fiche-metier');
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        /**
         * @var MissionActivite[] $activites
         * @var FicheposteActiviteDescriptionRetiree[] $retirees
         */
        $activites = $mission->getActivites();
        $retirees = $ficheposte->getDescriptionsRetireesByFicheMetierAndActivite($fichemetier, $mission);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            foreach ($activites as $description) {
                $found = null;
                foreach ($retirees as $retiree) {
                    if ($retiree->getHistoDestruction() === null and $retiree->getActivite() === $description) {
                        $found = $retiree;
                    }
                }
                $checked = (isset($data[$description->getId()]) and $data[$description->getId()] === "on");

                if ($found !== null and $checked) $this->getActivitesDescriptionsRetireesService()->delete($found);
                if ($found === null and !$checked) {
                    $item = new FicheposteActiviteDescriptionRetiree();
                    $item->setFichePoste($ficheposte);
                    $item->setFicheMetier($fichemetier);
                    $item->setMission($mission);
                    $item->setActivite($description);
                    $this->getActivitesDescriptionsRetireesService()->create($item);
                }
            }
            exit();
        }

        return new ViewModel([
            'title' => "Sélection de sous-activité pour l'activité [" . $mission->getLibelle() . "]",
            'ficheposte' => $ficheposte,
            'fichemetier' => $fichemetier,
            'mission' => $mission,
            'activites' => $activites,
            'retirees' => $retirees,
        ]);
    }

    /** EXPERTISE *****************************************************************************************************/

    public function ajouterExpertiseAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $expertise = new Expertise();
        $expertise->setFicheposte($ficheposte);

        $form = $this->getExpertiseForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/ajouter-expertise', ['fiche-poste' => $ficheposte->getId()], [], true));
        $form->bind($expertise);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getExpertiseService()->create($expertise);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une expertise",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierExpertiseAction(): ViewModel
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);

        $form = $this->getExpertiseForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/modifier-expertise', ['expertise' => $expertise->getId()], [], true));
        $form->bind($expertise);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getExpertiseService()->update($expertise);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une expertise",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserExpertiseAction(): Response
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);
        $this->getExpertiseService()->historise($expertise);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $expertise->getFicheposte()->getId()], [], true);
    }

    public function restaurerExpertiseAction(): Response
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);
        $this->getExpertiseService()->restore($expertise);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $expertise->getFicheposte()->getId()], [], true);
    }

    public function supprimerExpertiseAction(): ViewModel
    {
        $expertise = $this->getExpertiseService()->getRequestedExpertise($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getExpertiseService()->delete($expertise);
            //return $this->redirect()->toRoute('role', [], [], true);
            exit();
        }

        $vm = new ViewModel();
        if ($expertise !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'expertise " . $expertise->getLibelle(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-poste/supprimer-expertise', ["expertise" => $expertise->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** RIFSEEP ET NBI  ***********************************************************************************************/

    public function editerRifseepAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);

        $form = $this->getRifseepForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/editer-rifseep', ['fiche' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFichePosteService()->update($fiche);
            }
        }

        $vm = new ViewModel([
            'title' => 'Renseignement du RIFSEEP et du NBI',
            'form' => $form,
        ]);
        $vm->setTemplate('application/default/default-form');
        return $vm;
    }

    /** SPECIFICITE ***************************************************************************************************/

    public function editerSpecificiteAction(): ViewModel
    {
        $fiche = $this->getFichePosteService()->getRequestedFichePoste($this);

        $specificite = $fiche->getSpecificite();
        if ($specificite === null) {
            $specificite = new SpecificitePoste();
            $fiche->setSpecificite($specificite);
            $this->getSpecificitePosteService()->create($specificite);
        }

        /** @var SpecificitePosteForm $form */
        $form = $this->getSpecificitePosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-poste/editer-specificite', ['fiche' => $fiche->getId()], [], true));
        $form->bind($specificite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $specificite->setFiche($fiche);
                $this->getSpecificitePosteService()->update($specificite);
                $this->getFichePosteService()->update($fiche);
            }
        }

        return new ViewModel([
            'title' => 'Modifier spécificité du poste',
            'form' => $form,
        ]);
    }

    public function modifierRepartitionAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $fichetype = $this->getFichePosteService()->getFicheTypeExterne($this->params()->fromRoute('fiche-type'));

        $domaines = $fichetype->getFicheType()->getMetier()->getDomaines();
        $repartitions = $fichetype->getDomaineRepartitionsAsArray();

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $this->getFichePosteService()->updateRepatitions($fichetype, $data);
        }

        return new ViewModel([
            'title' => "Changement de la répartition entre domaines",
            'ficheposte' => $ficheposte,
            'fichetype' => $fichetype,
            'domaines' => $domaines,
            'repartitions' => $repartitions,
        ]);
    }

    public function validerAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $agent = $ficheposte->getAgent();
        $type = $this->params()->fromRoute('type');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = $ficheposte->getValidationActiveByTypeCode($type);
            if ($validation === null) {
                if ($data["reponse"] === "oui") {
                    $this->getValidationInstanceService()->setValidationActive($ficheposte, $type);
                    $this->getFichePosteService()->update($ficheposte);
                }
                if ($data["reponse"] === "non") {
                    $this->getValidationInstanceService()->setValidationActive($ficheposte, $type, 'Refus');
                    $this->getFichePosteService()->update($ficheposte);
                }
            }
            switch ($type) {
                case FichePosteValidations::VALIDATION_RESPONSABLE :
                    $this->getEtatInstanceService()->setEtatActif($ficheposte,FichePosteEtats::ETAT_CODE_OK);
                    $this->getFichePosteService()->update($ficheposte);
                    $this->getNotificationService()->triggerValidationResponsableFichePoste($ficheposte, $validation);
                    break;

                case FichePosteValidations::VALIDATION_AGENT :
                    $oldFiches = $this->getFichePosteService()->getFichesPostesSigneesActives($agent);
                    $this->getEtatInstanceService()->setEtatActif($ficheposte,FichePosteEtats::ETAT_CODE_SIGNEE);
                    $this->getFichePosteService()->update($ficheposte);

                    $newFiche = $this->getFichePosteService()->clonerFichePoste($ficheposte, true);
                    $newFiche->setAgent($ficheposte->getAgent());
                    $this->getEtatInstanceService()->setEtatActif($newFiche,FichePosteEtats::ETAT_CODE_REDACTION);
                    $this->getFichePosteService()->update($newFiche);

                    $date = new DateTime();
                    foreach ($oldFiches as $fiche) {
                        $fiche->setFinValidite($date);
                        $this->getFichePosteService()->update($fiche);
                    }

                    $this->getNotificationService()->triggerValidationAgentFichePoste($ficheposte, $validation);
                    break;
            }
            exit();
        }

        $titre = "Validation de la fiche de poste";
        $texte = "Validation de la fiche de poste";
        switch ($type) {
            case FichePosteValidations::VALIDATION_RESPONSABLE :
                $titre = "Validation du responsable de la fiche de poste de " . $ficheposte->getAgent()->getDenomination();
                $texte = "Cette validation rendra visible la fiche de poste à l'agent (" . $ficheposte->getAgent()->getDenomination() . ").<br/>";
                $texte .= "Suite à cette validation un courrier électronique sera envoyé à l'agent associé à la fiche de poste  (" . $ficheposte->getAgent()->getDenomination() . ") afin qu'il puisse valider à son tour celle-ci.<br/>";
                break;
            case FichePosteValidations::VALIDATION_AGENT :
                $responsables = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
                usort($responsables, function (AgentSuperieur $a, AgentSuperieur $b) {
                    $aaa = $a->getSuperieur()->getNomUsuel() . " " . $a->getSuperieur()->getPrenom();
                    $bbb = $b->getSuperieur()->getNomUsuel() . " " . $b->getSuperieur()->getPrenom();
                    return $aaa > $bbb;
                });
                $responsables = array_map(function (AgentSuperieur $a) {
                    return $a->getAgent()->getDenomination();
                }, $responsables);
                $responsables = implode(', ', $responsables);
                $titre = "Validation de l'agent de la fiche de poste de " . $ficheposte->getAgent()->getDenomination();
                $texte = "Cette validation finalise la fiche de poste de l'agent.<br/>";
                $texte .= "Suite à cette validation un courrier électronique sera envoyé au·x supérieur·e·s hiérachique·s direct·e·s de l'agent (" . $responsables . ") pour le·s informer de la validation de celle-ci.<br/>";
                break;
        }
        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
        $vm->setVariables([
            'title' => $titre,
            'text' => $texte,
            'action' => $this->url()->fromRoute('fiche-poste/valider', ["type" => $type, "fiche-poste" => $ficheposte->getId()], [], true),
            'refus' => false,
        ]);
        return $vm;
    }

    public function revoquerAction(): Response
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);

        switch ($validation->getType()->getCode()) {
            case FichePosteValidations::VALIDATION_RESPONSABLE :
                $this->getEtatInstanceService()->setEtatActif($ficheposte, FichePosteEtats::ETAT_CODE_REDACTION);
                $this->getFichePosteService()->update($ficheposte);
                break;
            case FichePosteValidations::VALIDATION_AGENT :
                $this->getEtatInstanceService()->setEtatActif($ficheposte, FichePosteEtats::ETAT_CODE_OK);
                $this->getFichePosteService()->update($ficheposte);
                break;
        }

        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $ficheposte->getId()], [], true);

    }

    public function actionAction(): ViewModel
    {
        $ficheposte = $this->getFichePosteService()->getRequestedFichePoste($this);

        return new ViewModel([
            'ficheposte' => $ficheposte,
        ]);
    }
}