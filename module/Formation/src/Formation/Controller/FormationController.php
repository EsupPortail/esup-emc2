<?php

namespace Formation\Controller;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Service\Agent\AgentServiceAwareTrait;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Form\ApplicationElement\ApplicationElementFormAwareTrait;
use Element\Form\CompetenceElement\CompetenceElementFormAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Form\Formation\FormationFormAwareTrait;
use Formation\Form\SelectionFormation\SelectionFormationFormAwareTrait;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Service\ActionCoutPrevisionnel\ActionCoutPrevisionnelServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationElement\FormationElementServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\PlanDeFormation\PlanDeFormationServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenEnquete\Service\Enquete\EnqueteServiceAwareTrait;
use UnicaenEnquete\Service\Resultat\ResultatServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class FormationController extends AbstractActionController
{
    use ActionCoutPrevisionnelServiceAwareTrait;
    use AgentServiceAwareTrait;
    use EnqueteServiceAwareTrait;
    use FormationElementServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PlanDeFormationServiceAwareTrait;
    use ResultatServiceAwareTrait;
    use SessionServiceAwareTrait;

    use ApplicationElementFormAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementFormAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FormationFormAwareTrait;
    use SelectionFormationFormAwareTrait;

    /** CRUD **********************************************************************************************************/

    public function indexAction(): ViewModel
    {
        $groupe = $this->params()->fromQuery('groupe');
        $groupe_ = ($groupe !== null and $groupe !== "") ? $this->getFormationGroupeService()->getFormationGroupe((int)$groupe) : null;
        $source = $this->params()->fromQuery('source');
        $historise = $this->params()->fromQuery('historise');
        $planId = $this->params()->fromQuery('planDeFormation');
        $planDeFormation = ($planId !== null && $planId !== '') ? $this->getPlanDeFormationService()->getPlanDeFormation($this->params()->fromQuery('planDeFormation')) : null;

        $formations = $this->getFormationService()->getFormationsByGroupe($groupe_);
        if ($planDeFormation !== null) $formations = array_filter($formations, function (Formation $formation) use ($planDeFormation) {
            return $formation->hasPlanDeFormation($planDeFormation);
        });

        if ($source !== null and $source !== "") $formations = array_filter($formations, function (Formation $a) use ($source) {
            return $a->getSource() === $source;
        });
        if ($historise !== null and $historise !== "") $formations = array_filter($formations, function (Formation $a) use ($historise) {
            if ($historise === "1") return $a->estHistorise();
            if ($historise === "0") return $a->estNonHistorise();
            return true;
        });

        return new ViewModel([
            'formations' => $formations,
            'plansDeFormation' => $this->getPlanDeFormationService()->getPlansDeFormation('libelle'),
            'planDeFormation' => $planDeFormation,
            'groupes' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
            'groupe' => $groupe,
            'source' => $source,
            'historise' => $historise,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        return new ViewModel([
            'title' => "Action de formation [" . $formation->getLibelle() . "]",
            'formation' => $formation,
            'coutsPrevisionnels' => $this->getActionCoutPrevisionnelService()->getActionsCoutsPrevisionnelsByAction($formation),
        ]);
    }

    public function ficheAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $sessions = $this->getSessionService()->getSessionsOuvertesByFormation($formation);
        $agent = $this->getAgentService()->getAgentByConnectedUser();

        return new ViewModel([
            'title' => "Action de formation [" . $formation->getLibelle() . "]",
            'formation' => $formation,
            'sessions' => $sessions,
            'agent' => $agent,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $formation = new Formation();
        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter', [], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {

                $this->getFormationService()->create($formation);
                $formation->setSource(HasSourceInterface::SOURCE_EMC2);
                $formation->setIdSource($formation->getId());
                $this->getFormationService()->update($formation);

                $url = $this->url()->fromRoute('formation/editer', ['formation' => $formation->getId()], ['force_canonical' => true], true);
                $this->flashMessenger()->addSuccessMessage("Action de formation <strong>" . $formation->getLibelle() . "</strong> créée. Pour accéder à celle-ci, vous pouvez utiliser le lien suivant : <a href='" . $url . "'>" . $url . "</a>");
                exit;
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/editer', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $sessions = $this->getSessionService()->getSessionsByFormation($formation);

        $vm = new ViewModel();
        $vm->setTemplate('formation/formation/modifier');
        $vm->setVariables([
            'title' => 'Edition d\'une formation',
            'formation' => $formation,
            'sessions' => $sessions,
            'form' => $form,
            'coutsPrevisionnels' => $this->getActionCoutPrevisionnelService()->getActionsCoutsPrevisionnelsByAction($formation),
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->historise($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function restaurerAction(): Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->restore($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function detruireAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormationService()->delete($formation);
            exit();
        }

        $vm = new ViewModel();
        if ($formation !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la formation [" . $formation->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/detruire', ["formation" => $formation->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** AUTRES MODIFICATIONS ******************************************************************************************/

    public function modifierFormationInformationsAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/modifier-formation-informations', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => 'Modifier les informations de la formation',
            'formation' => $formation,
            'form' => $form,
        ]);
        return $vm;
    }

    public function ajouterApplicationElementAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $hasApplicationElement = $this->getFormationService()->getRequestedFormation($this);

        if ($hasApplicationElement !== null) {
            $element = new ApplicationElement();

            $form = $this->getApplicationElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter-application-element', ['type' => $type, 'id' => $hasApplicationElement->getId()], [], true));
            $form->bind($element);
            $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getApplicationElementService()->create($element);
                    $hasApplicationElement->addApplicationElement($element);
                    $this->getFormationService()->update($hasApplicationElement);
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une application",
                'form' => $form,
            ]);
            $vm->setTemplate('default/default-form');
            return $vm;
        }
        exit();
    }

    public function ajouterCompetenceElementAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $hasCompetenceElement = $this->getFormationService()->getRequestedFormation($this);

        if ($hasCompetenceElement !== null) {
            $element = new CompetenceElement();

            $form = $this->getCompetenceElementForm();
            $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter-competence-element', ['type' => $type, 'id' => $hasCompetenceElement->getId()], [], true));
            $form->bind($element);
            $form->masquerClef();

            $request = $this->getRequest();
            if ($request->isPost()) {
                $data = $request->getPost();
                $form->setData($data);
                if ($form->isValid()) {
                    $this->getCompetenceElementService()->create($element);
                    $hasCompetenceElement->addCompetenceElement($element);
                    $this->getFormationService()->update($hasCompetenceElement);
                }
            }

            $vm = new ViewModel([
                'title' => "Ajout d'une competence",
                'form' => $form,
            ]);
            $vm->setTemplate('default/default-form');
            return $vm;
        }
        exit();
    }

    /** ACTIONS DE RECHERCHE ******************************************************************************************/

    public function rechercherFormationAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $formations = $this->getFormationService()->findFormationByTerm($term);
            $result = $this->getFormationService()->formatFormationsJSON($formations);
            return new JsonModel($result);
        }
        exit;
    }

    /** Retourne la liste des actions de formations dans au moins un plan de formation active (i.e. dont la date du jour
     *  est comprise dans la période) **/
    public function rechercherFormationsActivesAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $formations = $this->getFormationService()->findFormationsActivesByTerm($term);
            $result = $this->getFormationService()->formatFormationsJSON($formations);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherFormateurAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $formateurs = $this->getFormationService()->findFormateurByTerm($term);
            $result = $this->getFormationService()->formatFormateurJSON($formateurs);
            return new JsonModel($result);
        }
        exit;
    }

    /** DEBOULONNAGE **************************************************************************************************/

    public function dedoublonnerAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $form = $this->getSelectionFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/dedoublonner', ['formation' => $formation->getId()], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $formationSub = $this->getFormationService()->getFormation($data['formations'][0]);

            if ($formationSub and $formationSub !== $formation) {
                //décalages des éléments
                $elements = $this->getFormationElementService()->getElementsByFormation($formation);
                foreach ($elements as $element) {
                    $element->setFormation($formationSub);
                    $this->getFormationElementService()->update($element);
                }
                //décalage des instances
                $instances = $this->getSessionService()->getSessionsByFormation($formation);
                foreach ($instances as $instance) {
                    $instance->setFormation($formationSub);
                    $this->getSessionService()->update($instance);
                }

                //decalage des applications acquises
                $applications = $formation->getApplicationCollection();
                foreach ($applications as $application) {
                    if ($formationSub->hasApplication($application->getApplication())) {
                        $this->getApplicationElementService()->delete($application);
                    } else {
                        $formation->removeApplicationElement($application);
                        $formationSub->addApplicationElement($application);
                    }
                }
                //decalage des compétences acquises
                $competences = $formation->getCompetenceCollection();
                foreach ($competences as $competence) {
                    if ($formationSub->hasCompetence($competence->getCompetence())) {
                        $this->getCompetenceElementService()->delete($competence);
                    } else {
                        $formation->removeCompetenceElement($competence);
                        $formationSub->addCompetenceElement($competence);
                    }
                }
                //effacement final
                $this->getFormationService()->delete($formation);
                $this->getFormationService()->update($formationSub);
            }

        }

        $vm = new ViewModel();
        $vm->setTemplate('default/default-form');
        $vm->setVariables([
            'title' => "Sélection de la formation qui remplacera [" . $formation->getLibelle() . "]",
            'form' => $form,
        ]);
        return $vm;
    }


    public function ajouterPlanDeFormationAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $plans = $this->getPlanDeFormationService()->getPlansDeFormation();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $plan = $this->getPlanDeFormationService()->getPlanDeFormation($data['plan']);
            $formation->addPlanDeForamtion($plan);
            $this->getFormationService()->update($formation);
            exit();
        }

        $vm = new ViewModel([
            'title' => "Inclure dans un plan de formation",
            'formation' => $formation,
            'plans' => $plans,
        ]);
        return $vm;
    }

    public function retirerPlanDeFormationAction(): Response
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $plan = $this->getPlanDeFormationService()->getRequestedPlanDeFormation($this);

        $formation->removePlanDeFormation($plan);
        $this->getFormationService()->update($formation);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/editer', ['formation' => $formation->getId()], [], true);
    }

    public function resultatEnqueteAction(): ViewModel
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);

        $code_enquete = $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::CODE_ENQUETE);
        $enquete = $this->getEnqueteService()->getEnqueteByCode($code_enquete);

        $inscriptions = $this->getInscriptionService()->getInscriptionsByFormation($formation);
        [$counts, $results] = $this->getResultatService()->generateResultatArray($enquete, $inscriptions);

        $vm = new ViewModel([
            'enquete' => $enquete,
            'results' => $results,
            'counts' => $counts,
            'elements' => $inscriptions,
            'retourIcone' => "icon icon-retour",
            'retourLibelle' => "Accéder à l'action de formation",
            /** @see FormationController::editerAction() */
            'retourUrl' => $this->url()->fromRoute('formation/editer', ['formation' => $formation->getId()] ,[], true),
        ]);
        $vm->setTemplate('unicaen-enquete/resultat/resultats');
        return $vm;
    }
}